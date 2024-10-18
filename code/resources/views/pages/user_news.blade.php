@extends('layouts.app')

@section('title', 'My News')

@section('header', 'My News')

@section('content')
    <div class="search&sort">
        <input type="text" placeholder="Search for News...">
        <button>Search</button>
        <br>
    </div>

    @include('partials.filters')

    <div class="create_post">
        <button id="create_post">+</button>
    </div>

    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
    <br>

    <script src="{{ asset('js/create_post.js') }}"></script>
@endsection