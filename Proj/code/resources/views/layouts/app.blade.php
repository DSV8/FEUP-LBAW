<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/f1d77e88ed.js" crossorigin="anonymous"></script>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>@yield('title')</title>
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <!-- Styles -->
        @yield('styles')
        @yield('scripts')
        <script>
            const userId = "{{ auth()->id() }}"; // Get the authenticated user's ID
        </script>
        <script>
            const pusherAppKey = "{{ env('PUSHER_APP_KEY') }}";
            const pusherCluster = "{{ env('PUSHER_APP_CLUSTER') }}";
        </script>
        <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>

        <script src="{{ url('js/app.js') }}" defer>
        </script>

    </head>
    <body>
        <header>
            <h3>SWC News</h3>
            @auth
            <button id="notifications-btn"class="not-clicked" onclick="notifications()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                </svg>
            </button>
            <div id="notificationsContainer"></div>
            @endauth
        </header>
        <main>
            <h1>@yield('header')</h1>
            <nav>
                @guest
                <a href="{{ route('posts.top') }}">News Feed</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('login') }}">Login</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('register') }}">Register</a>
                @endguest
                
                @auth
                <a href="{{ route('posts.top') }}">News Feed</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('user_news') }}">User News</a>&nbsp;&nbsp;&nbsp;
                <a href="{{route('followed_topics')}}">Followed Topics</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('profile_page', ['id' => Auth::id()]) }}">Profile</a>&nbsp;&nbsp;&nbsp;

                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin_dashboard') }}">Admin Dashboard</a>&nbsp;&nbsp;&nbsp;
                <a href="{{route('admin_topic_proposals')}}">Topic Proposals Management</a>&nbsp;&nbsp;&nbsp;
                @endif
                
                @endauth

            </nav>
            <br>
            <div id="content">
                @yield('content')
            </div>
        </main>
        <footer>
            <hr>
            <nav>
                <a href="{{ route('about_us') }}">About Us</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('contact_us') }}">Contact Us</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('main_features') }}">Main Features</a>
            </nav>
            © SWC News
        </footer>
    </body>
</html>