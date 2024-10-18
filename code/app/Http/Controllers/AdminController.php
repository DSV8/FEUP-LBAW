<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('username', 'not like', '%anonymous%')->orderBy('id')->get();
        $contactUsRequests = ContactUs::orderByDesc('date')->get();

        return view('pages.admin_dashboard', [
            'users' => $users,
            'contactUsRequests' => $contactUsRequests, // Add this line
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|not_regex:/anonymous/',
            'email' => 'required|email|max:250|unique:user',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        // Save the user to the database
        $user->save();

        return redirect()->route('admin_dashboard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Admin $admin)
    {
        // Validate the request data.
        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255|not_regex:/anonymous/',
            'email' => 'nullable|email|max:255',
            'birthday' => 'nullable|date',
            'password' => 'nullable|string|min:8',
            'gender' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
        ]);

        // If a password is provided, hash it before storing.
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        // Update the user's profile information.
        $user->update($validatedData);

        // Save the changes to the database.
        $user->save();
    }
}
        
