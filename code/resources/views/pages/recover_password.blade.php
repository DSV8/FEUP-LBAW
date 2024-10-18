@extends('layouts.forms')

@section('content') 
    <header>
        <h1>Password Recovery</h1>
    </header>
    <main>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        @include('partials.emailform')
    </main>
@endsection

