@extends('layouts.forms')
<head>
    <title>Register</title>
</head>
@section('header')
    {{ "Register" }}
@endsection

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="name">Name*</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. John Doe">
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="username">Username*</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="e.g. johndoe_123">
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="email">E-Mail Address*</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com">
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password*</label>
    <input id="password" type="password" name="password" required placeholder="********">
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password*</label>
    <input id="password-confirm" type="password" name="password_confirmation" required placeholder="********">

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
    <a id="google_button" href="{{ route('google-auth') }}">
      <p>Login with <i class="fa-brands fa-google"></i></p>
    </a>
</form>
@endsection