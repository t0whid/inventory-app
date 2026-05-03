<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin User</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; max-width: 600px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; }
        .btn { margin-top: 20px; padding: 10px 15px; border: 0; border-radius: 4px; cursor: pointer; }
        .btn-save { background: #2563eb; color: white; }
        .btn-back { background: #6b7280; color: white; text-decoration: none; display: inline-block; }
        .error { color: red; font-size: 14px; }
        .alert-error { color: red; margin-top: 15px; }
    </style>
</head>
<body>

<h2>Edit Admin User</h2>

<a href="{{ route('admin.users.index') }}" class="btn btn-back">Back</a>

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $user->name) }}">
    @error('name') <div class="error">{{ $message }}</div> @enderror

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email) }}">
    @error('email') <div class="error">{{ $message }}</div> @enderror

    <label>Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
    @error('phone') <div class="error">{{ $message }}</div> @enderror

    <label>Password</label>
    <input type="password" name="password" placeholder="Leave empty if you do not want to change">
    @error('password') <div class="error">{{ $message }}</div> @enderror

    <label>Role</label>
    <select name="role">
        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
    </select>
    @error('role') <div class="error">{{ $message }}</div> @enderror

    <label>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="width:auto;">
        Active
    </label>

    <button type="submit" class="btn btn-save">Update</button>
</form>

</body>
</html>