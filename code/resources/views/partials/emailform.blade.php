<form class="content" method="POST" action="/send">
    @csrf
    <label for="email">Email*</label>
    <input id="email" type="email" name="email" placeholder="email@example.com" required>
    <input type="hidden" name="token" value="{{ bin2hex(random_bytes(16)) }}">
    <button type="submit">Recover</button>
</form>
