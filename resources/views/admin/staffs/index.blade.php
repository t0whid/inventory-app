@extends('layouts.admin')

@section('title', 'Staffs')
@section('page_title', 'Staffs')
@section('page_subtitle', 'Manage staff accounts for inventory operations')

@section('content')
<div class="page-card">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="mb-1">Staff List</h5>
            <p class="text-muted mb-0">Create, edit and manage staff login accounts.</p>
        </div>

        <a href="{{ route('admin.staffs.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            Create Staff
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staffs as $staff)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $staff->name }}</div>
                        </td>

                        <td>{{ $staff->phone }}</td>

                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fa-solid fa-id-badge me-1"></i>
                                {{ $staff->role ?: 'Staff' }}
                            </span>
                        </td>

                        <td>
                            @if($staff->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.staffs.edit', $staff) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.staffs.destroy', $staff) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this staff?')">
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
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fa-solid fa-users-slash fa-2x mb-2"></i>
                            <div>No staff found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $staffs->links() }}
    </div>

</div>
@endsection