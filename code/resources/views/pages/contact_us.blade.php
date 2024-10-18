@extends('layouts.app')

@section('title', 'Contact Us')

@section('header', 'Contact Us')

@section('content')
    
    
    <form enctype="multipart/form-data" action="{{ route('contact_us.create') }}" method="POST">
        {{ csrf_field() }}
        <label for="first_name">First Name*</label>
        <input type="text" id="first_name" name="first_name" required placeholder="e.g. John">
        <label for="last_name">Last Name*</label>
        <input type="text" id="last_name" name="last_name" required placeholder="e.g. Doe">
        <label for="email">Email*</label>
        <input type="text" id="email" name="email" required placeholder="email@example.com">
        <label for="message">Message*</label>
        <textarea id="message" name="message" required placeholder="e.g. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at pellentesque lectus, id consectetur nunc."></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>
@endsection
