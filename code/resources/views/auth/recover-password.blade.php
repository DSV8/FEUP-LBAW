@extends('layouts.forms')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <?php $token = $_GET['token']; ?>
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="password">Password*</label>
            <input id="password" type="password" name="password" required placeholder="********">
        </div>

        <div>
            <label for="password_confirmation">Confirm Password*</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="********">
        </div>

        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>
@endsection
