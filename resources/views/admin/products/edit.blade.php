@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('page_subtitle', 'Update product information')

@section('content')
<div class="page-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Edit: {{ $product->product_name }}</h5>

        <a href="{{ route('admin.products.index') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="product_name"
                    value="{{ old('product_name', $product->product_name) }}"
                    class="form-control"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input
                    type="text"
                    name="category"
                    value="{{ old('category', $product->category) }}"
                    class="form-control"
                >
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
                        value="{{ old('cost_price', $product->cost_price) }}"
                        class="form-control"
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
                        value="{{ old('selling_price', $product->selling_price) }}"
                        class="form-control"
                    >
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Shelf Life Days <span class="text-danger">*</span></label>
                <input
                    type="number"
                    min="0"
                    name="shelf_life_days"
                    value="{{ old('shelf_life_days', $product->shelf_life_days) }}"
                    class="form-control"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
                <input
                    type="number"
                    min="0"
                    name="reorder_level"
                    value="{{ old('reorder_level', $product->reorder_level) }}"
                    class="form-control"
                >
                <div class="form-text">Low stock alert will use this value later.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-light">Cancel</a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Update Product
            </button>
        </div>
    </form>

</div>
@endsection