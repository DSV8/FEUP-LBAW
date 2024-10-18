@section('styles')
    <link href="{{ url('css/postcard.css') }}" rel="stylesheet">
@endsection
<script type="text/javascript" src={{ url('js/post.js') }} defer></script>

<div class="postcard" data-id="{{ $post->id }}">
<article class="post">
    <h2>{{ $post->title }}</h2>
    <div class="post-info">
        <strong>by </strong>
        @if(!str_contains($post->user->username, 'anonymous'))
            <strong><a href="{{ route('profile_page', ['id' => $post->user->id]) }}">{{ $post->user->name }}</a></strong>
        @else
            <strong class="anonymous_username">{{$post->user->username}}</strong>
        @endif
        <strong>on {{ date('Y-m-d', strtotime($post->postdate)) }} at {{ date('H:i:s', strtotime($post->postdate))}}</strong>
        <br>
        <p>{{ $post->topic->title ?? 'N/A' }}</p>
        @if(!empty($images))
            @include('partials.carousel', ['images' => $images])
        @endif
        @if(isset($userFollowedTopics))
            <button class="followButton" onclick="toggleFollow({{ $post->topic->id }})" data-topic-id="{{ $post->topic->id }}">
                {{ in_array($post->topic->id, $userFollowedTopics) ? 'Unfollow' : 'Follow' }} {{ $post->topic->title }}
            </button>
        @endif
        <br><br>
        <p><strong>{{ $post->caption }}</strong></p><br><br>
        @php
            $userUpvoted = $post->checkIfUserUpvoted();
            $userDownvoted = $post->checkIfUserDownvoted();
            $id = $post->id;
            $upvoteId = $id . "upvoteButton";
            $downvoteId = $id . "downvoteButton";
        @endphp
        @auth
        <br>
        <button id="{{$upvoteId}}" class="{{ $userUpvoted ? 'clicked' : 'not-clicked' }}" onclick="upvote({{ $post->id }})">
            @if($userUpvoted)
                <i class="fa-solid fa-circle-up"></i>
            @else
                <i class="fa-regular fa-circle-up"></i>
            @endif
        </button>

        <div class="upvotes-downvotes" data-id="{{$post->id}}">
            <strong>{{ $post->upvotes - $post->downvotes }}</strong>
        </div>

        <button id="{{$downvoteId}}" class="{{ $userDownvoted ? 'clicked' : 'not-clicked' }}" onclick="downvote({{ $post->id }})">
            @if($userDownvoted)
                <i class="fa-solid fa-circle-down"></i>
            @else
                <i class="fa-regular fa-circle-down"></i>
            @endif
        </button>
        @endauth
        <br>
        <i class="fa-regular fa-comment"></i> <strong>{{ $post->comments->count() }}</strong><br><br>
    </div>
</article>
</div>
