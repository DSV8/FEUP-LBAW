@extends('layouts.app')

@section('title')
    {{ "Profile" }}
@endsection

@section('header')
    {{ "Profile" }}
@endsection

@section('content')
    <!-- Add content for profile page -->
    
    <div class="main_box"> 
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Auth::user()->isAdmin() || Auth::user()->id == $user->id)
            @include('partials.profile_edit', ['user' => $user])
        @else
            @include('partials.profile_view', ['user' => $user])
        @endif
    </div>
            
@endsection