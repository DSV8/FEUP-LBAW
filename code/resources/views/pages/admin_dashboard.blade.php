@extends('layouts.app')

@section('title')
    {{ "Admin Dashboard" }}
@endsection

@section('header')
    {{ "Admin Dashboard" }}
@endsection

@section('content')
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Birthday</th>
                <th>Reputation</th>
                <th>URL</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @if (!str_contains($user->username, 'anonymous'))
                    @include('partials.user_row_admin', ['user' => $user])
                @endif
            @endforeach
        </tbody>
    </table>

    <h2>Create New Account</h2>
    <form action="{{ route('admin.register') }}" method="POST">
        {{ csrf_field() }}

        <label for="name">Name*</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe">
        @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
        @endif

        <label for="username">Username*</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required placeholder="e.g. johndoe_123">
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

        <button type="submit">Add Account</button>
    </form>
    <hr>
    @include('partials.contact_us_requests', ['contactUsRequests' => $contactUsRequests])
@endsection
