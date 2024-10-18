@foreach($posts as $post)
    @include('partials.post', ['post' => $post, 'userFollowedTopics' => $userFollowedTopics])
@endforeach
<script src="{{ asset('js/follow_topic.js') }}"></script>
<script src="{{ asset('js/readmore_post.js') }}"></script>
