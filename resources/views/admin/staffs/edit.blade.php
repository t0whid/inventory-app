@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page_title', 'Edit Staff')
@section('page_subtitle', 'Update staff account information')

@section('content')
<div class="page-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Edit: {{ $staff->name }}</h5>

        <a href="{{ route('admin.staffs.index') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    <form action="{{ route('admin.staffs.update', $staff) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">New PIN</label>

                <div class="input-group">
                    <input
                        type="password"
                        name="pin"
                        id="pinInput"
                        class="form-control"
                        placeholder="Leave empty if unchanged"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="8"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    >

                    <button class="btn btn-outline-secondary" type="button" id="togglePin">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <div class="form-text">Enter only if you want to change staff PIN. Only digits allowed.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Role</label>
                <input type="text" name="role" value="{{ old('role', $staff->role) }}" class="form-control">
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        id="isActive"
                        {{ old('is_active', $staff->is_active) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="isActive">Active Staff</label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.staffs.index') }}" class="btn btn-light">Cancel</a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Update Staff
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const pinInput = document.getElementById('pinInput');
    const togglePin = document.getElementById('togglePin');

    togglePin?.addEventListener('click', function () {
        const icon = this.querySelector('i');

        if (pinInput.type === 'password') {
            pinInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            pinInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush