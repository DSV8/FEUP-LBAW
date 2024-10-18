<?php
 
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\UpvotePost;
use App\Models\DownvotePost;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImagePostController;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;



use App\Events\Upvote;
use App\Events\Downvote;
use App\Events\UndoDownvote;
use App\Events\UndoUpvote;

use App\Notifications\UpvotedPost;
use App\Notifications\DownvotedPost;

class PostController extends Controller
{
    /**
     * Show the Post for a given id.
     */
    public function show(string $id): View
    {
        // Get the post.
        $post = Post::findOrFail($id);

        // Get all comments for the post ordered by date.
        /* $comments = $post->comments()->orderByDesc('commentdate')->get(); */
        $comments = $post->comments()->orderByRaw('(upvotes - downvotes) DESC')->with('image')->get();
        // Get all images that belong to the Post.
        $images = $post->images();

        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post,
            'comments' => $comments,
            'images' => $images,
        ]);
    }
    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'search_term' => ['required']
        ]);
    
        $searchTerm = $validatedData['search_term'];
    
        $searchTerm = preg_replace('/\s+/', ' ', $searchTerm);
    
        if (strpos($searchTerm, ' ') !== false) {
            // Full-text search for terms with spaces
            $modifiedSearchTerm = str_replace(' ', ':* & ', $searchTerm) . ':*';
            $posts = Post::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$modifiedSearchTerm])
                ->get();
        } else {
            // Exact match search for both title and caption
            $posts = Post::where('title', 'ILIKE', "%$searchTerm%")
            ->orWhere('caption', 'ILIKE', "%$searchTerm%")
            ->get();
        }
        $user = auth()->user();

        if ($user) {
            // Retrieve the topics that the current user follows
            $userFollowedTopics = $user->followedTopics()->pluck('id')->toArray();
        }
        else{
            $userFollowedTopics = [];
        }
    
        return view('pages.search_results', compact('posts', 'userFollowedTopics'));

    }
    
    /**
     * Shows all posts sorted by Upvotes/Downvote Difference
     */
    public function listTop()
    {
        // Fetch the authenticated user
        $user = auth()->user();
    
        // Get posts ordered by the difference between upvotes and downvotes.
        $posts = Post::orderByRaw('(upvotes - downvotes) DESC')->get();
        if ($user) {
            // Retrieve the topics that the current user follows
            $userFollowedTopics = $user->followedTopics()->pluck('id')->toArray();
        }
        else{
            $userFollowedTopics = [];
        }
    
        // Use the pages.post template to display all cards.
        return view('pages.main', compact('posts', 'userFollowedTopics'));
    }

    public function userNews()
    {
        $userId = Auth::id();
        $posts = Post::where('user_id', $userId)->orderBy('upvotes', 'DESC')->get();
        $user = auth()->user();
        if($user){
            $userFollowedTopics = $user->followedTopics()->pluck('id')->toArray();
    
        }
        else{
            $userFollowedTopics = [];
        }
    
        return view('pages.user_news', [
            'posts' => $posts,
            'userFollowedTopics' => $userFollowedTopics,
        ]);
    }

    /**
     * Creates a new post.
     */
    public function create(Request $request) {
        $request->validate([
            'title' => 'required',
            'caption' => 'required',
            'images' => 'array',
            'topic' => 'string',
            'images.*' =>  'image|max:2048|mimes:jpg,jpeg,svg,gif,png',
        ]);
    
        // $topicId = getTopicId($request->topic);

        // Set post details.
        $post = Auth::user()->posts()->create([
            //'topic_id' => $topicId,
            'title' => $request->input('title'),
            'caption' => $request->input('caption'),
            'postdate' => now(), // Set the current date and time.
            'user_id' => Auth::user()->id
        ]);

        
        $topicId = $request->input('topic_id');
        $topic = Topic::find($topicId);
        if ($topic) {
            $post->topic()->associate($topic);
            $post->save();
        }
    

        // Check if images array is not null
        if (!empty($request->images)) {
            // Call the create method of ImagePostController and pass the images array
            $errorImages = ImagePostController::create($request, $post->id);
            if ($errorImages === true) {
                // No errors, continue with the code
            } else {
                // Delete any images associated with the post
                ImagePost::where('post_id', $post->id)->delete();

                // Delete the post
                $post->delete();

                // Return error Message                
                return redirect()->route('pages.create_post')->withErrors($errorImages);
            }

        }
        // Redirect the user to the newly created post page or any other page you prefer.
        return redirect()->route('user_news')->with('success', 'Post created successfully');
    }

    /**
     * Update a post.
     */
    public function update(Request $request, $id){
        try {

            // Find the post.
            $post = Post::findOrFail($id);
            
            $this->authorize('update', $post);


            $request->validate([
                'title' => ['required'],
                'caption' => ['required']
            ]);
            
            // Update post details.
            $post->title = $request->input('title');
            $post->caption = $request->input('caption');

            // Save the updated post.
            $post->save();

            return redirect()->route('posts.show', ['id' => $post->id])->with('success', 'Post updated successfully');
        } catch (\Exception $e) {
            // Log the error message.
            \Log::error('Failed to update post with ID: ' . $post->id . '. Error: ' . $e->getMessage());

            // Redirect back with an error message.
            return redirect()->route('home')->with('error',  'Failed to update the post');
        }
    }

    /**
     * Delete a post.
     */
    public function delete(Request $request, $id)
    {
        // Find the post.
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        if(($post->upvotes != 0 || $post->downvotes != 0 || !empty($post->comments)) && !Auth::user()->isAdmin()){
            return redirect()->route('posts.show', ['id' => $post->id])->withErrors(['message' => 'Cannot delete post because it has been voted or commented on.']);
        }
        
        try {
            $post->delete();
            \Log::info('Post deleted successfully with ID: ' . $post->id);
            return redirect()->route('posts')->with('success', 'Post deleted successfully');
        } 
        catch (\Exception $e) {
            \Log::error('Failed to delete post with ID: ' . $post->id . '. Error: ' . $e->getMessage());
            
            return redirect()->route('home')->with('error', 'Failed to delete the post');
        }
    }

    function upvote(Request $request) {
        \Log::info('Upvote PHP');
        $postId = $request->id;
        $userId = Auth::id();
        
        $upvotePost = new UpvotePost();
        $upvotePost->post_id = $postId;
        $upvotePost->user_id = $userId;
        
        if ($upvotePost->save()) {
            event(new Upvote($postId));
        } else {
            \Log::info('Failed to upvote post with ID: ' . $postId);
        }
        $post = Post::findOrFail($postId);
        $rep = $post->upvotes - $post->downvotes;
        $postOwner = User::findOrFail($post->user_id);
        $upvoter = User::findOrFail($userId);
        $postOwner->notify(new UpvotedPost($upvoter, $post));
        return response()->json($rep, 200);
    }

    function undoupvote(Request $request) {
        \Log::info('Undoupvote PHP');
        $postId = $request->id;
        $userId = Auth::id();
        $upvotePost = UpvotePost::where('post_id', $postId)
        ->where('user_id', $userId)
        ->first();
        if ($upvotePost) {
            $upvotePost->delete();
            event(new UndoUpvote($postId));

        } else {
            \Log::info('Upvote not found for post with ID: ' . $postId);
        }
        $post = Post::findOrFail($postId);
        $rep = $post->upvotes - $post->downvotes;
        return response()->json($rep, 200);
    }

    function downvote(Request $request) {
        $postId = $request->id;
        $userId = Auth::id();
        
        $downvotePost = new DownvotePost();
        $downvotePost->post_id = $postId;
        $downvotePost->user_id = $userId;
        
        if ($downvotePost->save()) {
            event(new Downvote($postId));
        } else {
            \Log::info('Failed to downvote post with ID: ' . $postId);
        }
        $post = Post::findOrFail($postId);
        $rep = $post->upvotes - $post->downvotes;
        $postOwner = User::findOrFail($post->user_id);
        $downvoter = User::findOrFail($userId);
        $postOwner->notify(new DownvotedPost($downvoter, $post));
        return response()->json($rep, 200);
    }

    function undodownvote(Request $request) {
        $postId = $request->id;
        $userId = Auth::id();
        $downvotePost = DownvotePost::where('post_id', $postId)
        ->where('user_id', $userId)
        ->first();

        if ($downvotePost) {
            $downvotePost->delete();
            event(new UndoDownvote($postId));

        } else {
            \Log::info('Downvote not found for post with ID: ' . $postId);
        }
        $post = Post::findOrFail($postId);
        $rep = $post->upvotes - $post->downvotes;
        return response()->json($rep, 200);
    }

    public function followedTopics(Request $request){
        $user = Auth::user();
        $userFollowedTopics = $user->followedTopics()->pluck('id')->toArray();
        $posts = Post::whereIn('topic_id', $userFollowedTopics)
            ->orderBy('postdate', 'DESC')
            ->get();

        
        return view('pages.followed_topics', [
            'posts' => $posts,
            'userFollowedTopics' => $userFollowedTopics,
        ]);
    }

    public function applyFilter(Request $request)
    { 


        $query = Post::query();

        if ($request->has('sort')) {
            $sortQuery = $request->input('sort');

            switch ($sortQuery) {
                case 'dateDown':
                    $query->orderBy('postdate', 'DESC');
                    break;
                case 'dateUp':
                    $query->orderBy('postdate', 'ASC');
                    break;
                case 'voteDown':
                    $query->orderBy('upvotes', 'DESC');
                    break;
                case 'voteUp':
                    $query->orderBy('upvotes', 'ASC');
                    break;
                default:
                    $query->orderBy('postdate', 'DESC');
                    break;
            }
        } else {
            $query->orderBy('upvotes', 'DESC');
        }


        // Time-based filtering
        if ($request->filled('time_sort')) {
            $timeSort = $request->input('time_sort');
            if($timeSort != 'all_time'){

                switch ($timeSort) {
                    case 'last_24_hours':
                        $query->where('postdate', '>=', now()->subHours(24));
                        break;
                    case 'last_week':
                        $query->where('postdate', '>=', now()->subWeek());
                        break;
                    case 'last_month':
                        $query->where('postdate', '>=', now()->subMonth());
                        break;
                    case 'last_year':
                        $query->where('postdate', '>=', now()->subYear());
                        break;

                }
            }
        }

        // Topic Filtering
        if ($request->filled('topic_filter') && $request->input('topic_filter') !== '0') {
            $topicId = $request->input('topic_filter');
            $query->where('topic_id', $topicId);
        }
        
        // Minimum Date
        if ($request->filled('minimum_date')) {
            $minDate = $request->input('minimum_date');
            $query->whereDate('postdate', '>=', $minDate);
        }

        // Maximum Date
        if ($request->filled('maximum_date')) {
            $maxDate = $request->input('maximum_date');
            $query->whereDate('postdate', '<=', $maxDate);
        }

        // Minimum Upvotes
        if ($request->filled('minimum_upvote')) {
            $minUpvotes = $request->input('minimum_upvote');
            $query->where('upvotes', '<=', $minUpvotes);
        }

        // Maximum Upvotes
        if ($request->filled('maximum_upvote')) {
            $maxUpvotes = $request->input('maximum_upvote');
            $query->where('upvotes', '<=', $maxUpvotes);
        }

        // Minimum Downvotes
        if ($request->filled('minimum_downvote')) {
            $minDownvotes = $request->input('minimum_downvote');
            $query->where('downvotes', '>=', $minDownvotes);
        }

        // Maximum Downvotes
        if ($request->filled('maximum_downvote')) {
            $maxDownvotes = $request->input('maximum_downvote');
            $query->where('downvotes', '<=', $maxDownvotes);
        }

        //User ID
        if ($request->filled('user_id')) {
            $userId = $request->input('user_id');
            $query->where('user_id', $userId);
        }

        $user = auth()->user();
        if ($request->has('followedTopics')) {
            $user = auth()->user();
            $userFollowedTopics = $user ? $user->followedTopics()->pluck('id')->toArray() : [];
            $query->whereIn('topic_id', $userFollowedTopics);
        }
        $posts = $query->get();
        if($user){
            $userFollowedTopics = $user->followedTopics()->pluck('id')->toArray();
        }
        else{
            $userFollowedTopics = [];
        }
        

        $filteredPostsHtml = view('partials.posts', compact('posts', 'userFollowedTopics'))->render();
        return response()->json(['success' => true, 'html' => $filteredPostsHtml]);
    }
}
?>