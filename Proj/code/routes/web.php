<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UserController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\TopicProposalController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Home
Route::get('/', function () {
    return redirect('/main');
})->name('home');


Route::get('/user_news', function () {
    return view('pages.user_news');
})->name('user_news')->middleware('auth');


// User
Route::middleware('auth')->group(function () {
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::post('/change-password', [UserController::class, 'change_password'])->name('change.password');
    Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile_page');
    Route::delete('/profile/{id}/delete', [UserController::class, 'delete'])->name('profile_delete');

    Route::post('/profile/block', [UserController::class, 'block'])->name('user.block');
    Route::post('/profile/unblock', [UserController::class, 'unblock'])->name('user.unblock');
});

// ImageUser
Route::middleware('auth')->group(function () {
    Route::post('/profile/{id}/image', [ImageController::class, 'createUserImage'])->name('image.new');
});
Route::post('/profileimage', [ImageController::class, 'getAJAX']);

// Create Post
Route::get('/create_post', function () {
    return view('pages.create_post');
})->name('create_post')->middleware('auth');

// Main Page Routes
Route::group(['prefix' => 'main'], function () {
    Route::post('/main', [PostController::class, 'applyFilter'])->name('filter.posts.apply');
    Route::get('/', [PostController::class, 'listTop'])->name('posts.top');
});


//user news
Route::get('/user_news', [PostController::class, 'userNews'])->name('user_news')->middleware('auth'); 

Route::get('/followed_topics', [PostController::class, 'followedTopics'])->name('followed_topics')->middleware('auth'); 


// Individual Post Actions
Route::prefix('posts')->group(function () {
    Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/create_post', [PostController::class, 'create'])->name('posts.create');
    Route::delete('/{id}/delete', [PostController::class, 'delete'])->name('posts.delete');
    Route::patch('/{id}/update', [PostController::class, 'update'])->name('posts.update');
});


Route::post('/post/upvote', [PostController::class, 'upvote']);
Route::post('/post/undoupvote', [PostController::class, 'undoupvote']);
Route::post('/post/downvote', [PostController::class, 'downvote']);
Route::post('/post/undodownvote', [PostController::class, 'undodownvote']);

//Notification
Route::post('/unreadnotifications', [NotificationController::class, 'unreadNotifications']);

// Comments
Route::post('/posts/{id}/comments', [CommentController::class, 'create'])->name('comments.create');
Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::post('/comments/{id}/update', [CommentController::class, 'updateComment'])->name('comments.update');
Route::delete('/comments/{id}/delete', [CommentController::class, 'delete'])->name('comments.delete');
Route::post('/comment/upvote', [CommentController::class, 'upvote']);
Route::post('/comment/undoupvote', [CommentController::class, 'undoupvote']);
Route::post('/comment/downvote', [CommentController::class, 'downvote']);
Route::post('/comment/undodownvote', [CommentController::class, 'undodownvote']);

// Admin
Route::middleware('admin')->group(function () {
    Route::get('/admin_dashboard', [AdminController::class, 'index'])->name('admin_dashboard');
    Route::post('/admin_dashboard', [AdminController::class, 'create'])->name('admin.register');
    Route::get('/admin_topic_proposals',[TopicProposalController::class,'listProposals'])->name('admin_topic_proposals');
    Route::post('/create-topic/{proposal}', [TopicProposalController::class, 'createTopic'])->name('create_topic');
    Route::delete('/delete-proposal/{proposal}', [TopicProposalController::class, 'deleteProposal'])->name('delete_proposal');
});

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/recover-password', [MailController::class, 'showRecoverPassForm'])->name('recover-password');
Route::post('/send', [MailController::class, 'send']);
Route::get('/reset-password', function () {
    return view('auth.recover-password');
})->name('password.reset');
Route::post('/recover-password', [UserController::class, 'recoverPassword'])->name('password.update');
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleController::class, 'callbackGoogle'])->name('google-call-back');

//Topics
Route::get('/create_post', [TopicController::class, 'showCreatePostForm'])->name('create_post_topics');
Route::get('/get_topics',  [TopicController::class, 'showFiltersTopic'])->name('get_filters_with_topics');

//Topic Follow
Route::post('/topics/{topicId}/toggle-follow', [TopicController::class, 'toggleFollow'])->name('toggle_follow');


Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::middleware('auth')->group(function () {
    Route::post('/follow/{id}', [UserController::class, 'follow'])->name('follow');
});

//Topic Proposal
Route::get('/topic_proposal', function () {
    return view('pages.topic_proposal');
})->middleware('auth');
Route::post('/create_topic_proposal', [TopicProposalController::class,'create'])->name('createTopicProposal')->middleware('auth');

//Searches
Route::get('/user_search', [UserController::class, 'search'])->name('users.search');
Route::get('/post_search', [PostController::class, 'search'])->name('posts.search');
Route::get('/comment_search', [CommentController::class, 'search'])->name('comments.search');


Route::get('/about_us', function () {return view('pages.about_us');})->name('about_us');
Route::get('/contact_us', function () {return view('pages.contact_us');})->name('contact_us');
Route::get('/main_features', function () {return view('pages.main_features');})->name('main_features');

//Contact Us
Route::post('/admin_dashboard/contact_us', [ContactUsController::class, 'create'])->name('contact_us.create');
?>