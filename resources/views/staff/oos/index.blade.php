@extends('layouts.staff')

@section('title', 'OOS Marking')
@section('page_title', 'OOS Marking')

@section('content')
<div class="page-card mb-3">
    <form method="GET" action="{{ route('staff.oos.index') }}">
        <label class="form-label fw-semibold">Select Date</label>

        <div class="input-group">
            <input type="date" name="date" value="{{ $date }}" class="form-control">
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-calendar-check me-1"></i>
                Load
            </button>
        </div>
    </form>
</div>

<form action="{{ route('staff.oos.store') }}" method="POST">
    @csrf

    <input type="hidden" name="date" value="{{ $date }}">

    <div class="page-card mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Mark Out Of Stock</h5>
                <p class="text-muted mb-0 small">
                    Tap products that are out of stock.
                </p>
            </div>

            <span class="badge bg-danger" id="selectedCount">
                0 selected
            </span>
        </div>

        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input
                    type="text"
                    id="productSearch"
                    class="form-control"
                    placeholder="Search product..."
                >
            </div>
        </div>

        @forelse($products as $product)
            <label class="oos-item product-row">
                <input
                    type="checkbox"
                    name="product_ids[]"
                    value="{{ $product->id }}"
                    class="oos-checkbox"
                    {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }}
                >

                <div class="oos-box">
                    <div>
                        <h6 class="mb-1 product-name">{{ $product->product_name }}</h6>
                        <div class="small text-muted">
                            {{ $product->category ?: 'No Category' }}
                        </div>
                    </div>

                    <div class="check-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
            </label>
        @empty
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                <div>No active products found.</div>
            </div>
        @endforelse

        @if($products->count() > 0)
            <div class="sticky-save">
                <button type="submit" class="btn btn-danger w-100 py-3">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Save OOS Items
                </button>
            </div>
        @endif
    </div>
</form>

<div class="page-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">OOS List</h5>
            <p class="text-muted mb-0 small">
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </p>
        </div>

        <span class="badge bg-danger">
            {{ $oosList->count() }} items
        </span>
    </div>

    @forelse($oosList as $oos)
        <div class="border rounded-3 p-3 mb-2">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h6 class="mb-1">{{ $oos->product->product_name ?? 'Product Deleted' }}</h6>
                    <div class="small text-muted">
                        Staff: {{ $oos->staff->name ?? 'Unknown' }}
                    </div>
                </div>

                <span class="badge bg-light text-dark">
                    {{ $oos->marked_time ? \Carbon\Carbon::parse($oos->marked_time)->format('h:i A') : '-' }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-circle-check fa-2x mb-2"></i>
            <div>No OOS item marked for this date.</div>
        </div>
    @endforelse
</div>
@endsection

@push('styles')
<style>
    .oos-item {
        display: block;
        cursor: pointer;
        margin-bottom: 12px;
    }

    .oos-item input {
        display: none;
    }

    .oos-box {
        border: 1px solid #e5e7eb;
        background: #ffffff;
        border-radius: 14px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }

    .check-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #d1d5db;
        color: transparent;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
    }

    .oos-item input:checked + .oos-box {
        border-color: #dc2626;
        background: #fef2f2;
    }

    .oos-item input:checked + .oos-box .check-icon {
        background: #dc2626;
        border-color: #dc2626;
        color: #ffffff;
    }

    .sticky-save {
        position: sticky;
        bottom: 76px;
        background: #ffffff;
        padding-top: 12px;
        z-index: 50;
    }
</style>
@endpush

@push('scripts')
<script>
    const checkboxes = document.querySelectorAll('.oos-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const productSearch = document.getElementById('productSearch');

    function updateSelectedCount() {
        const total = document.querySelectorAll('.oos-checkbox:checked').length;
        selectedCount.innerText = total + ' selected';
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    productSearch?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        document.querySelectorAll('.product-row').forEach(function (row) {
            const name = row.querySelector('.product-name')?.innerText.toLowerCase() || '';

            row.style.display = name.includes(keyword) ? 'block' : 'none';
        });
    });

    updateSelectedCount();
</script>
@endpush