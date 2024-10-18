<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordRecovery;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class UserController extends Controller
{
    private static $counter = 1;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $users = User::where('username', 'not like', '%anonymous%')->get();
        return view('users.index', ['users' => $users]);
    }

    public function create(array $data)
    {
        // Create a new user instance
        $user = new User([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        // Save the user to the database
        $user->save();

        // Redirect to a success page or return a response
        return redirect()->route('registration.success')->with('success', 'User registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        if (str_contains($user->username, 'anonymous')) {
            return redirect()->route('home')->with('error', 'This profile has been deleted.');
        }

        // Check if the current user can see (show) the user.
        $this->authorize('show', Auth::user());  
        
        // Return the profile view to display the user.
        return view('pages.profile', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Check if the current user can update the profile.
            $this->authorize('update', $user);
    
            // Validate the request data.
            $validatedData = $request->validate([
                'username' => 'nullable|string|max:255|',
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'birthday' => 'nullable|date',
                'gender' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'url' => 'nullable|url|max:255',
            ]);

        // Remove null values from the validated data.
        $filteredData = array_filter($validatedData, function ($value) {
            return $value !== null;
        });     

        // Update the user's profile information.
        $user->update($filteredData);

        // Save the changes to the database.
        $user->save();

        // Redirect the user back to their profile page.
        return redirect()->route('profile_page', ['id' => $user->id])->with('success', 'Info updated successfully');
    
        } catch (\Exception $e) {
            // Log the error message.
            \Log::error('Failed to update user with ID: ' . $user->id . '. Error: ' . $e->getMessage());
    
            // Redirect back with an error message.
            return redirect()->route('profile_page', ['id' => $user->id])->with('error', 'Failed to update info');
        }
    }    

    /**
     * Update the image of a user in the user table.
     */
    public static function updateImage(int $userId, int $imageId): void
    {
        // Find the user by ID
        $user = User::findOrFail($userId);

        // Update the user's image
        $user->image_id = $imageId;

        // Save the changes to the database
        $user->save();
    }

    /**
     * Change user password.
     */
    public function change_password(Request $request)
    {
        try{
            $request->validate([
                'last_password' => ['required'],
                'new_password' => ['required', 'string', 'min:8'],
            ]);

            $user = Auth::user();

            // Verify if the provided last password is correct
            if (!password_verify($request->input('last_password'), $user->password)) {
                throw ValidationException::withMessages([
                    'last_password' => ['The provided last password is incorrect.'],
                ]);
            }

            // Update the user's password with the new one
            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            return redirect()->route('logout')->with('success', 'Password changed successfully.');
        } catch (ValidationException $e) {
            return redirect()->route('profile')->withErrors($e->validator->errors());
        } catch (\Exception $e) {

            return redirect()->route('profile')->with('error', 'Failed to update password.');
        }
    }

/*     public function search(Request $request)
    {
        $validatedData = $request->validate([
            'search_term' => ['required']
        ]);
    
        $searchTerm = $validatedData['search_term'];
    
        $searchTerm = preg_replace('/\s+/', ' ', $searchTerm);
    
        // Exact match search for both name and username
        $results = User::where('name', 'ILIKE', "%$searchTerm%")
            ->orWhere('username', 'ILIKE', "%$searchTerm%")
            ->orWhere('username', 'NOT LIKE', '%anonymous%')
            ->get();

        return view('pages/users_search_results', ['results' => $results]);
    }
 */

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'search_term' => ['required']
        ]);

        $searchTerm = $validatedData['search_term'];

        $searchTerm = preg_replace('/\s+/', ' ', $searchTerm);

        $users = User::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$searchTerm])
            ->get();

        return view('pages/search_results', [
            'users' => $users
        ]);
    }

    public function block(Request $request){
        $validatedData = $request->validate([
            'id' => ['required']
        ]);

        $user = User::findOrFail($validatedData['id']);

        $user->blocked = 1;
        $saveResult = $user->save();

        if ($saveResult) {
            return response()->json(['status' => 'success', 'message' => 'Successfully blocked user'], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to block user'], 500);
    }

    public function unblock(Request $request){
        $validatedData = $request->validate([
            'id' => ['required']
        ]);

        $user = User::findOrFail($validatedData['id']);

        $user->blocked = 0;
        $saveResult = $user->save();

        if ($saveResult) {
            return response()->json(['status' => 'success', 'message' => 'Successfully unblocked user'], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to unblock user'], 500);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        // Find the user by ID.
        $user = User::findOrFail($id);
        
        // Check if the current user can delete the user.
        $this->authorize('delete', $user);

        $counter = Cache::get('counter', 1);

        // Delete the user.
        $user->username = 'anonymous' . ($counter);
        $user->email = 'anonymous' . ($counter) . '@example.com';
        $user->password = bcrypt('inaccessible_password'); // Change the password to something inaccessible
        $counter++;
        Cache::put('counter', $counter, now()->addYears(10));
        
        $user->save();


        if(Auth::user()->isAdmin()){
            // Redirect the user to the admin dashboard.
            return redirect()->route('admin_dashboard');
        }
        else{
            // Redirect the user to the user index page.
            return redirect()->route('logout');
        }
    }

    public function showUserProfile($userId) {
    $user = User::findOrFail($userId);
    $isFollowing = auth()->check() && auth()->user()->followingUsers->contains($user);

    return view('profile_view', [
        'user' => $user,
        'isFollowing' => $isFollowing,
    ]);
}

    /**
     * Follow or unfollow a user.
     */
    public function follow(Request $request)
    {
        try {
            $followedUserId = $request->input('followed_id');
            $authUser = auth()->user();
            $userToFollow = User::findOrFail($followedUserId);

            $isFollowing = $authUser->followingUsers->contains($userToFollow);

            if ($isFollowing) {
            // If already following, unfollow
                $authUser->followingUsers()->detach($userToFollow);
            } else {
                // If not following, follow
                $authUser->followingUsers()->attach($userToFollow);
            }

            // Fetch updated counts after follow/unfollow
            $followersCount = $userToFollow->followersUsers()->count();
            $followingCount = $userToFollow->followingUsers()->count();
            $authUserFollowersCount = $authUser->followersUsers()->count();
            $authUserFollowingCount = $authUser->followingUsers()->count();
            
            return response()->json([
                'followersCount' => $followersCount,
                'followingCount' => $followingCount,
                'authUserFollowersCount' => $authUserFollowersCount,
                'authUserFollowingCount' => $authUserFollowingCount,
                'isFollowing' => !$isFollowing,
            ]);
        } catch (\Exception $e) {
            Log::error('Follow Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function recoverPassword(Request $request){

        $validatedData = $request->validate([
            'password' => 'required',
            'token' => 'required',
        ]);

        if($passwordRecovery = PasswordRecovery::where('token', $request->input('token'))->where('expiration_date', '>', now())->first()){
                // Hash the password
                $hashedPassword = Hash::make($validatedData['password']);

                // Alter the password in the database where the user_id is the same
                User::where('id', $passwordRecovery->user_id)->update(['password' => $hashedPassword]);

                // Delete the password recovery entry
                $passwordRecovery->delete();

                Auth::logout();

                return redirect()->route('login')->with('success', 'Password changed successfully.');
        }

        else{
            return redirect()->route('login')->with('error', 'Password recovery request expired.');
        }

    }
}
