@extends('layouts.app')

@section('title', 'Edit Comment')

@section('content')
    <h2>Edit Comment</h2>
    <form enctype="multipart/form-data" action="{{ route('comments.update', ['id' => $comment->id]) }}" method="POST">
        @csrf
        <div>
            <label for="title">Title*</label>
            <input type="text" id="title" name="title" value="{{ $comment->title }}" required placeholder="e.g. Lorem Ipsum">
        </div>
        <div>
            <label for="caption">Caption*</label>
            <textarea id="caption" name="caption" required placeholder="e.g. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at pellentesque lectus, id consectetur nunc.">{{ $comment->caption }}</textarea>
        </div>
        <button type="submit">Update</button>
    </form>
@endsection
