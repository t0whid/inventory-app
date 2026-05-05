@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Manage inventory products and reorder settings')

@section('content')
<div class="page-card">

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h5 class="mb-1">Product List</h5>
            <p class="text-muted mb-0">Create, edit and manage product information.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            Create Product
        </a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-4">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search by product, item code or category"
                >
            </div>
        </div>

        <div class="col-12 col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-dark flex-fill">
                <i class="fa-solid fa-filter me-1"></i>
                Filter
            </button>

            <a href="{{ route('admin.products.index') }}" class="btn btn-light flex-fill">
                Reset
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">SL</th>
                    <th>Product</th>
                    <th>Item Code</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>Selling</th>
                    <th>Reorder</th>
                    <th>Shelf Life</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            {{ $products->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $product->product_name }}</div>
                        </td>

                        <td>
                            @if($product->item_code)
                                <span class="badge bg-light text-dark border">
                                    {{ $product->item_code }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>
                            {{ $product->category ?: '-' }}
                        </td>

                        <td>
                            ₹{{ number_format($product->cost_price, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($product->selling_price, 2) }}
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $product->reorder_level }}
                            </span>
                        </td>

                        <td>
                            {{ $product->shelf_life_days }} days
                        </td>

                        <td>
                            @if($product->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
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
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                            <div>No product found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $products->links() }}
    </div>

</div>
@endsection