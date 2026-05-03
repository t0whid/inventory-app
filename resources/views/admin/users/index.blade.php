@extends('layouts.admin')

@section('title', 'Admin Users')
@section('page_title', 'Admin Users')
@section('page_subtitle', 'Manage admin and super admin accounts')

@section('content')
<div class="page-card">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="mb-1">Admin User List</h5>
            <p class="text-muted mb-0">Create, edit and manage admin panel users.</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            Create Admin User
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th class="d-none d-md-table-cell">Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="small text-muted d-md-none">{{ $user->email ?? '-' }}</div>
                        </td>

                        <td>{{ $user->phone }}</td>

                        <td class="d-none d-md-table-cell">
                            {{ $user->email ?? '-' }}
                        </td>

                        <td>
                            @if($user->role === 'super_admin')
                                <span class="badge bg-danger">
                                    <i class="fa-solid fa-crown me-1"></i>
                                    Super Admin
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    <i class="fa-solid fa-user-shield me-1"></i>
                                    Admin
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this admin user?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-solid fa-users-slash fa-2x mb-2"></i>
                            <div>No admin users found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

</div>
@endsection