@extends('layouts.app')

@section('title')
    {{ "Profile" }}
@endsection

@section('header')
    {{ "Profile" }}
@endsection

@section('content')
    <div class="create_post">
        <button id="create_post">+</button>
    </div>
    <a href="/topic_proposal">Create Topic Proposal</a><br>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Sort Options Dropdown --}}
    <div class="sort-options">
        <label for="sort_by">Sort By:</label>
        <select id="sort_by" name="sort">
            <option value="voteDown">Votes - Most to Least</option>
            <option value="voteUp">Votes - Least to Most</option>
            <option value="dateDown" selected>Date - Newest to Oldest</option>
            <option value="dateUp">Date - Oldest to Newest</option>
        </select>
        
        <!-- Time Sort Dropdown -->
        <label for="time_sort">Time Sort:</label>
        <select id="time_sort" name="time_sort">
            <option value="all_time">All Time</option>
            <option value="last_24_hours">Last 24 Hours</option>
            <option value="last_week">Last Week</option>
            <option value="last_month">Last Month</option>
            <option value="last_year">Last Year</option>
        </select>
    </div>


    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
    
@endsection
<script>  
   var filterPostsApplyRoute  = @json(route('filter.posts.apply'));
</script>
<script src="{{ asset('js/create_post.js') }}"></script>
<script src="{{asset('js/filters.js')}}"></script>