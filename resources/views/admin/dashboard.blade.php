@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Inventory overview and quick summary')

@section('content')
<div class="row g-3">

    <div class="col-12 col-md-6 col-xl-3">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Today Sales</p>
                    <h4 class="mb-0">₹0</h4>
                </div>
                <div class="fs-3 text-primary">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">OOS Count</p>
                    <h4 class="mb-0">0</h4>
                </div>
                <div class="fs-3 text-danger">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Wastage</p>
                    <h4 class="mb-0">₹0</h4>
                </div>
                <div class="fs-3 text-warning">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Low Stock</p>
                    <h4 class="mb-0">0</h4>
                </div>
                <div class="fs-3 text-success">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="page-card mt-4">
    <h5 class="mb-2">Welcome to Inventory Admin Panel</h5>
    <p class="text-muted mb-0">
        Dashboard data will be connected after stock, wastage and OOS modules are completed.
    </p>
</div>
@endsection