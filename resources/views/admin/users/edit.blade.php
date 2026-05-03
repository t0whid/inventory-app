@extends('layouts.admin')

@section('title', 'Edit Admin User')
@section('page_title', 'Edit Admin User')
@section('page_subtitle', 'Update admin account information')

@section('content')
<div class="page-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Edit: {{ $user->name }}</h5>

        <a href="{{ route('admin.users.index') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave empty if unchanged">
            </div>

            <div class="col-md-6">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        id="isActive"
                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="isActive">Active User</label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Update User
            </button>
        </div>
    </form>

</div>
@endsection