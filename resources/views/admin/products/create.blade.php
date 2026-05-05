@extends('layouts.admin')

@section('title', 'Create Product')
@section('page_title', 'Create Product')
@section('page_subtitle', 'Add a new product to inventory')

@section('content')
<div class="page-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">New Product</h5>

        <a href="{{ route('admin.products.index') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="product_name"
                    value="{{ old('product_name') }}"
                    class="form-control"
                    placeholder="Chocolate Pastry"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Item Code</label>
                <input
                    type="text"
                    name="item_code"
                    value="{{ old('item_code') }}"
                    class="form-control"
                    placeholder="Example: ITM-001"
                >
                <div class="form-text">Optional product code.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input
                    type="text"
                    name="category"
                    value="{{ old('category') }}"
                    class="form-control"
                    placeholder="Pastry / Cake / Pizza"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Cost Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="cost_price"
                        value="{{ old('cost_price', 0) }}"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="selling_price"
                        value="{{ old('selling_price', 0) }}"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Shelf Life Days <span class="text-danger">*</span></label>
                <input
                    type="number"
                    min="0"
                    name="shelf_life_days"
                    value="{{ old('shelf_life_days', 0) }}"
                    class="form-control"
                    placeholder="Example: 2"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
                <input
                    type="number"
                    min="0"
                    name="reorder_level"
                    value="{{ old('reorder_level', 0) }}"
                    class="form-control"
                    placeholder="Example: 10"
                    required
                >
                
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Save Product
            </button>
        </div>
    </form>

</div>
@endsection