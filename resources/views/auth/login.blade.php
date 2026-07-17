<div>
    <form action="{{ route('auth.authenticate') }}" method="post">
        @csrf
        <div>
            <label for="user_id">User ID</label>
            <input type="text" name="user_id" id="user_id" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
