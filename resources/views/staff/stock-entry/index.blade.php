@extends('layouts.staff')

@section('title', 'Stock Entry')
@section('page_title', 'Daily Stock Entry')

@section('content')
<div class="page-card mb-3">
    <form method="GET" action="{{ route('staff.stock-entry.index') }}" id="stockFilterForm">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Select Date</label>
                <input
                    type="date"
                    name="date"
                    value="{{ $date }}"
                    class="form-control"
                    id="stockDateInput"
                >
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Select Product</label>
                <select name="product_id" class="form-select" id="productSelect">
                    <option value="">Choose product</option>

                    @foreach($products as $product)
                        <option
                            value="{{ $product->id }}"
                            {{ (string) $selectedProductId === (string) $product->id ? 'selected' : '' }}
                        >
                            {{ $product->product_name }} {{ $product->category ? '('.$product->category.')' : '' }}
                        </option>
                    @endforeach
                </select>

                <div class="form-text">
                    Product select korlei auto load hobe.
                </div>
            </div>
        </div>
    </form>
</div>

@if(!$selectedProduct)
    <div class="page-card text-center py-5">
        <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
        <h5 class="mb-1">Select a product</h5>
        <p class="text-muted mb-0">
            Choose date and product from dropdown to enter daily stock.
        </p>
    </div>
@else
    <form action="{{ route('staff.stock-entry.store') }}" method="POST">
        @csrf

        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">

        {{-- Hidden only for frontend closing calculation. Not shown to staff. --}}
        <input type="hidden" id="wastageQty" value="{{ $stock->wastage_qty ?? 0 }}">

        <div class="page-card">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h5 class="mb-1">{{ $selectedProduct->product_name }}</h5>
                    <p class="text-muted mb-0 small">
                        {{ $selectedProduct->category ?: 'No Category' }}
                    </p>
                </div>

                <span class="badge bg-success">
                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                </span>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Opening Stock</label>
                    <input
                        type="number"
                        readonly
                        value="{{ $stock->opening_stock ?? $previousOpeningStock }}"
                        class="form-control bg-light stock-calc"
                        id="openingStock"
                    >
                    <div class="form-text">
                        Auto from previous closing stock.
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Production Qty</label>
                    <input
                        type="number"
                        min="0"
                        name="production_qty"
                        value="{{ old('production_qty', $stock->production_qty ?? 0) }}"
                        class="form-control stock-calc"
                        id="productionQty"
                        inputmode="numeric"
                    >
                </div>

                <div class="col-12">
                    <label class="form-label">Sales Qty</label>
                    <input
                        type="number"
                        min="0"
                        name="sales_qty"
                        value="{{ old('sales_qty', $stock->sales_qty ?? 0) }}"
                        class="form-control stock-calc"
                        id="salesQty"
                        inputmode="numeric"
                    >
                </div>

                <div class="col-12">
                    <div class="closing-box">
                        <div>
                            <span class="text-muted">Closing Stock</span>
                            <h3 class="mb-0" id="closingStock">0</h3>
                        </div>

                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-3 mt-4">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Save Daily Stock
            </button>
        </div>
    </form>
@endif
@endsection

@push('styles')
<style>
    input,
    select {
        min-height: 44px;
        font-size: 16px;
    }

    .closing-box {
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        border-radius: 16px;
        padding: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .closing-box i {
        color: #16a34a;
        font-size: 32px;
    }

    .closing-box h3 {
        color: #047857;
    }

    .closing-box.negative {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .closing-box.negative i,
    .closing-box.negative h3 {
        color: #dc2626;
    }
</style>
@endpush

@push('scripts')
<script>
    const stockFilterForm = document.getElementById('stockFilterForm');
    const productSelect = document.getElementById('productSelect');
    const stockDateInput = document.getElementById('stockDateInput');

    productSelect?.addEventListener('change', function () {
        if (this.value) {
            stockFilterForm.submit();
        }
    });

    stockDateInput?.addEventListener('change', function () {
        if (productSelect?.value) {
            stockFilterForm.submit();
        }
    });

    const openingStock = document.getElementById('openingStock');
    const productionQty = document.getElementById('productionQty');
    const salesQty = document.getElementById('salesQty');
    const wastageQty = document.getElementById('wastageQty');
    const closingStock = document.getElementById('closingStock');
    const closingBox = document.querySelector('.closing-box');

    function toNumber(value) {
        return parseInt(value || 0);
    }

    function calculateClosing() {
        if (!openingStock || !productionQty || !salesQty || !wastageQty || !closingStock) {
            return;
        }

        const opening = toNumber(openingStock.value);
        const production = toNumber(productionQty.value);
        const sales = toNumber(salesQty.value);
        const wastage = toNumber(wastageQty.value);

        const closing = opening + production - sales - wastage;

        closingStock.innerText = closing;
        closingBox?.classList.toggle('negative', closing < 0);
    }

    document.querySelectorAll('.stock-calc').forEach(function(input) {
        input.addEventListener('input', calculateClosing);
    });

    calculateClosing();
</script>
@endpush