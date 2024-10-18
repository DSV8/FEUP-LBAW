<div class="search">
    <select id="search_category" name="search_category">
        <option value="posts" selected>News</option>
        <option value="users">Users</option>
        <option value="comments">Comments</option>
    </select>

    <input id="search_term" name="search_term" type="text" value="{{ old('search_term') }}" placeholder="Search for News/Comments/Users...">
    @if ($errors->has('search_term'))
        <span class="error">
            {{ $errors->first('search_term') }}
        </span>
    @endif

    <button id="search_button">Search</button>
</div>

<script src="{{ asset('js/search.js') }}"></script>
<script>
    const postsSearchRoute = "{{ route('posts.search') }}";

</script>
