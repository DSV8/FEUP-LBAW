<div class="comments">
        @foreach ($comments as $comment)
            <div class = "commentcard">
            <article class="comment">
                <h3>{{ $comment->title }}</h3><strong>by</strong> <a href="{{ route('profile_page', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
                <strong>on {{ date('Y-m-d', strtotime($comment->postdate)) }} at {{ date('H:i:s', strtotime($comment->postdate))}}</strong><br><br>
                <p>{{ $comment->caption }}</p>
                @if($comment->image)
                    <img src="{{ asset('post/'.$comment->image->filename) }}" alt="Comment Image">
                @endif
                <br>
            @php
                $userUpvotedComment = $comment->checkIfUserUpvoted();
                $userDownvotedComment = $comment->checkIfUserDownvoted();
                $id = $comment->id;
                $upvoteId = $id . "upvoteCommentButton";
                $downvoteId = $id . "downvoteCommentButton";
                $repId = $id . "upvotes-downvotes-comment";
            @endphp
            @auth
            <br>
            <button id="{{$upvoteId}}" class="{{ $userUpvotedComment ? 'clicked' : 'not-clicked' }}" onclick="upvoteComment({{ $comment->id }})">
                @if($userUpvotedComment)
                    <i class="fa-solid fa-circle-up"></i>
                @else
                    <i class="fa-regular fa-circle-up"></i>
                @endif
            </button>

            <div id="{{$repId}}">
                <strong>{{ $comment->upvotes - $comment->downvotes }}</strong>
            </div>

            <button id="{{$downvoteId}}" class="{{ $userDownvotedComment ? 'clicked' : 'not-clicked' }}" onclick="downvoteComment({{ $comment->id }})">
                @if($userDownvotedComment)
                    <i class="fa-solid fa-circle-down"></i>
                @else
                    <i class="fa-regular fa-circle-down"></i>
                @endif
            </button>
            @endauth
                @can('update', $comment)
                    <form action="{{ route('comments.edit', ['id' => $comment->id]) }}" method="GET">
                        @csrf
                        <button type="submit">Edit</button>
                    </form>
                @endcan
                @can('delete', $comment)
                    <form action="{{ route('comments.delete', ['id' => $comment->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @endcan
            </article>
            </div>
        @endforeach
    <br><hr><br>
</div>