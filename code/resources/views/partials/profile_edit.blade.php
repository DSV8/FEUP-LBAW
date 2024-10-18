@section('scripts')
    <script src="{{ asset('js/follow.js') }}" defer></script>
@endsection
<script src="https://kit.fontawesome.com/f1d77e88ed.js" crossorigin="anonymous"></script>
<form enctype="multipart/form-data" action="{{ route('image.new', ['id' => $user->id]) }}" method="POST">
    {{csrf_field()}}
    <div class="op_box">
        <div class="circle_container">
        <label for="image_input" id="label_file">
            <img src="{{ $user->getProfileImage() }}" alt="Profile Picture">
        </label>  
            <div class="overlay">
                <input type="file" name="image" accept="image/*" id="image_input">
                    <div class="text">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
            </div>
        </div> 
    </div>
    <br>
    <div class="btnSubmit">
        <button type="submit">Save Image</button>
    </div>
</form>
<form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST">
    {{ csrf_field() }}
                @method('PUT')
                <div class="op_box">
                    <p>Username*</p>
                    <input id="username" type="text" name="username" value="{{ old('username') ?? $user->username }}" placeholder="e.g. johndoe_123" required></input>
                    @if ($errors->has('username'))
                        <span class="error">
                            {{ $errors->first('username') }}
                        </span>
                    @endif                    
                </div>
                <div class="op_box">
                    <p>Name*</p>
                    <input id="name" type="text" name="name" value="{{ old('name') ?? $user->name }}" placeholder="e.g. John Doe" required></input>
                    @if ($errors->has('name'))
                        <span class="error">
                            {{ $errors->first('name') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Birthday</p>
                    <input id="birthday" type="date" name="birthday" value="{{ old('birthday') ?? $user->birthday}}"></input>
                    @if ($errors->has('birthday'))
                        <span class="error">
                            {{ $errors->first('birthday') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Country</p>
                    <input id="country" type="text" name="country" value="{{ old('country') ?? $user->country }}" placeholder="e.g. Portugal"></input>
                    @if ($errors->has('country'))
                        <span class="error">
                            {{ $errors->first('country') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Gender</p>
                    <input id="gender" type="text" name="gender" value="{{ old('gender') ?? $user->gender }}" placeholder="e.g. Male"></input>
                    @if ($errors->has('gender'))
                        <span class="error">
                            {{ $errors->first('gender') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>URL</p>
                    <input id="url" type="text" name="url" value="{{ old('url') ?? $user->url }}" placeholder="https://www.example.com"></input>
                    @if ($errors->has('url'))
                        <span class="error">
                            {{ $errors->first('url') }}
                        </span>
                    @endif   
                </div>
                <div class="op_box">
                    <p>Email*</p>
                    <input id="email" type="email" name="email" value="{{ old('email') ?? $user->email }}" placeholder="email@example.com" required></input>
                    @if ($errors->has('email'))
                        <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif   
                </div>
                <div class="save_button_settings">
                    <div class="btnSubmit">
                        <button type="submit">SAVE</button>
                    </div>
                </div>
</form>
<p>Reputation: {{ $user['reputation'] }}</p>
<p>Followers: <span id="followersCount">{{ $user['followers']}}</span></p>
<p>Following: <span id="followingCount">{{ $user['following']}}</span></p>

@if(Auth::check() && Auth::user()->id !== $user->id)
    @php
        $isFollowing = Auth::user()->followingUsers->contains($user);
    @endphp
    <button id="followButton" onclick="toggleFollow({{ $user->id }})">
        {{ $isFollowing ? 'Unfollow' : 'Follow' }}
    </button>
@endif
            <h4 class="box_header_title">Change password</h4>
            <form action="{{ route('change.password') }}" method="POST">
                {{ csrf_field() }}
                <div class="op_box">
                    <p>Last password*</p>
                    <input id="last_password" type="password" name="last_password" value="{{ old('last_password') }}" required placeholder="********">
                </div>
                <div class="op_box">
                    <p>New password*</p>
                    <input id="new_password" type="password" name="new_password" value="{{ old('new_password') }}" required placeholder="********">
                </div>
                <div class="btnSubmit">
                    <button type="submit">SAVE</button>
                </div>
            </form>

            @if(Auth::user()->id == $user->id)
            <form method="GET" action="{{ route('logout') }}">
                <button type="submit" id="logout">Logout</button>
            </form>
            @endif

            <form id='delete_form' action="{{ route('profile_delete', ['id' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                {{ csrf_field() }}
                {{ method_field('DELETE') }} 
                <button type="submit" id="delete_account">Delete Account</button>
            </form>
