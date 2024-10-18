@extends('layouts.app')

@section('title', 'Contact Us')

@section('header', 'Main Features')

@section('content')
<p>Here are the main features of our website:</p>
<ul>
    <li><i class="fa-regular fa-newspaper"></i> - <a href="{{ route('home') }}">News Feed</a></li>
    <li><i class="fa-solid fa-newspaper"></i> - <a href="{{ route('user_news') }}">News you Posted</a></li>
    <li><i class="fa-regular fa-user"></i> - <a>View & Edit your Profile</a></li>
    <li><i class="fa-solid fa-user-slash"></i> - <a>Delete your Account</a></li>
    <li><i class="fa-regular fa-square-plus"></i> - <a href="{{ url('/create_post') }}">Create a Post</a></li>
    <li><i class="fa-regular fa-pen-to-square"></i> - <a>Edit & Delete your Posts</a></li>
    <li><i class="fa-solid fa-comment"></i> - <a>Comment on a Post & Edit your Comments</a></li>
    <li><i class="fa-solid fa-comment-slash"></i> - <a>Delete your Comments</a></li>
    <li><i class="fa-solid fa-magnifying-glass"></i> - <a>Search for Users/Posts/Comments</a></li>
    <li><i class="fa-solid fa-user-plus"></i> - <a>Follow & Unfollow other Users</a></li>
    <li><i class="fa-solid fa-tags"></i> - <a>Follow & Unfollow Topics</a></li>
    <li><i class="fa-solid fa-ban"></i> - <a>Block & Unblock Users</a></li>
    <li><i class="fa-solid fa-arrow-up-long"></i><i class="fa-solid fa-arrow-down-long"></i> - <a>Upvote & Downvote Posts/Comments</a></li>
    <li><i class="fa-solid fa-headset"></i> - <a href="{{ route('contact_us') }}">Support</a></li>
</ul>
@endsection
