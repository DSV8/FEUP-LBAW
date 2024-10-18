@extends('layouts.app')

@section('title', 'Search Results')

@section('header', 'Search Results')

@section('content')
    @if(isset($posts) && $posts !== null)
        @include('partials.posts', ['posts' => $posts])
    @endif
    @if(isset($users) && $users !== null && count($users) > 0)
        <div>
            <h2>Users</h2>
            <ul>
                @foreach($users as $user)
                    <li>
                        Username: <a href="{{ route('profile_page', ['id' => $user->id]) }}">{{ $user->username }}</a><br>
                        Name: {{ $user->name }} <br>
                        Reputation: {{ $user->reputation }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(isset($comments) && $comments !== null && count($comments) > 0)
    <div>
        <h2>Comments</h2>
        <ul>
            @foreach($comments as $comment)
                <li>
                    Post Title: <a href="{{ route('posts.show', ['id' => $comment->post->id]) }}">{{ $comment->post->title }}</a> <br>
                    Commenter Username: <a href="{{ route('profile_page', ['id' => $comment->user->id]) }}">{{ $comment->user->username }}</a> <br>
                    Comment Title: {{ $comment->title }} <br>
                    Comment Caption: {{ $comment->caption }} <br>
                    Upvotes: {{$comment->upvotes}} <br>
                    Downvotes: {{$comment->downvotes}} <br>
                </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection