@extends('layouts.app')

@section('title')
    {{ "Admin Dashboard" }}
@endsection

@section('header')
    {{ "Admin Dashboard" }}
@endsection

@section('content')
<h1>Topic Proposals</h1>
    <ul>
        @foreach($proposals as $proposal)
            <li>
                <p>Title: {{ $proposal->title }}</p>
                <p>Caption: {{ $proposal->caption }}</p>
                <form method="POST" action="{{ route('create_topic', ['proposal' => $proposal->id]) }}">
                    @csrf
                    <button type="submit">Create Topic</button>
                </form>
                <form method="POST" action="{{ route('delete_proposal', ['proposal' => $proposal->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete Proposal</button>
                </form>
            </li>
        @endforeach
@endsection

