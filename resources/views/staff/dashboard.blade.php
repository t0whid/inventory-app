@extends('layouts.staff')

@section('title', 'Dashboard')
@section('page_title', 'Staff Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="page-card">
            <h5 class="mb-1">Welcome, {{ $loggedStaff->name ?? session('staff_name') }}</h5>
            <p class="text-muted mb-0">
                Use the bottom menu to enter stock, wastage and OOS information.
            </p>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Stock Entry</p>
                    <h5 class="mb-0">Pending</h5>
                </div>
                <div class="fs-2 text-success">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Wastage Entry</p>
                    <h5 class="mb-0">Pending</h5>
                </div>
                <div class="fs-2 text-warning">
                    <i class="fa-solid fa-trash"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">OOS Marking</p>
                    <h5 class="mb-0">Pending</h5>
                </div>
                <div class="fs-2 text-danger">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection