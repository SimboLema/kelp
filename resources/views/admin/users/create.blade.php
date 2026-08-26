<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name') }}"
            required
        >
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old('email') }}"
            required
        >
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input
            type="password"
            name="password"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label>Confirm Password</label>
        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Create User
    </button>
</form>
