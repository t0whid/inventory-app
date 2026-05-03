<!DOCTYPE html>
<html>
<head>
    <title>Admin Users</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #f5f5f5; text-align: left; }
        .btn { padding: 8px 12px; text-decoration: none; border: 0; cursor: pointer; border-radius: 4px; }
        .btn-create { background: #16a34a; color: white; }
        .btn-edit { background: #2563eb; color: white; }
        .btn-delete { background: #dc2626; color: white; }
        .success { color: green; margin-bottom: 10px; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>Admin Users</h2>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="error">{{ session('error') }}</div>
@endif

<a href="{{ route('admin.users.create') }}" class="btn btn-create">+ Create Admin User</a>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-edit">Edit</a>

                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline-block;"
                          onsubmit="return confirm('Are you sure you want to delete this admin user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No admin users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $users->links() }}
</div>

</body>
</html>