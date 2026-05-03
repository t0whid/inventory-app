@extends('layouts.admin')

@section('title', 'Create Staff')
@section('page_title', 'Create Staff')
@section('page_subtitle', 'Add a new staff login account')

@section('content')
<div class="page-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">New Staff</h5>

        <a href="{{ route('admin.staffs.index') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    <form action="{{ route('admin.staffs.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Staff name">
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="9876543210">
            </div>

            <div class="col-md-6">
                <label class="form-label">PIN <span class="text-danger">*</span></label>

                <div class="input-group">
                    <input
                        type="password"
                        name="pin"
                        id="pinInput"
                        class="form-control"
                        placeholder="4 to 8 digit PIN"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="8"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    >

                    <button class="btn btn-outline-secondary" type="button" id="togglePin">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <div class="form-text">Staff will login using phone and PIN. Only digits allowed.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Role</label>
                <input type="text" name="role" value="{{ old('role') }}" class="form-control" placeholder="Cashier / Kitchen / Manager">
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" checked>
                    <label class="form-check-label" for="isActive">Active Staff</label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.staffs.index') }}" class="btn btn-light">Cancel</a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Save Staff
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