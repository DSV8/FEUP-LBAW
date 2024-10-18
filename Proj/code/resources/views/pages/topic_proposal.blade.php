@extends('layouts.app')

@section('title')
    {{ "Profile" }}
@endsection

@section('header')
    {{ "Profile" }}
@endsection

@section('content')
    <div>
        <h2>Create Topic Proposal</h2>
        <form method="POST" action="{{ route('createTopicProposal') }}">
            @csrf
            <div>
                <label for="topic_name">Topic Name<span>*</span></label>
                <input type="text" id="topic_name" name="topic_name" placeholder="e.g. Lorem Ipsum" required>
            </div>
            <div>
                <label for="caption">Caption<span>*</span></label>
                <textarea id="caption" name="caption" placeholder="e.g. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at pellentesque lectus, id consectetur nunc." required></textarea>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection