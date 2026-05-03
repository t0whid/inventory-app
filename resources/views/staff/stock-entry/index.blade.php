@extends('layouts.staff')

@section('title', 'Stock Entry')
@section('page_title', 'Daily Stock Entry')

@section('content')
<div class="page-card mb-3">
    <form method="GET" action="{{ route('staff.stock-entry.index') }}">
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

<form action="{{ route('staff.stock-entry.store') }}" method="POST">
    @csrf

    <input type="hidden" name="date" value="{{ $date }}">

    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Products</h5>
                <p class="text-muted mb-0 small">
                    Enter opening, production and sales quantity.
                </p>
            </div>

            <span class="badge bg-success">
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </span>
        </div>

        @forelse($products as $index => $product)
            @php
                $stock = $stocks->get($product->id);
            @endphp

            <div class="stock-item border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <h6 class="mb-1">{{ $product->product_name }}</h6>
                        <div class="small text-muted">
                            {{ $product->category ?: 'No Category' }}
                        </div>
                    </div>

                    <span class="badge bg-light text-dark">
                        Reorder: {{ $product->reorder_level }}
                    </span>
                </div>

                <input type="hidden" name="stocks[{{ $index }}][product_id]" value="{{ $product->id }}">

                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <label class="form-label small">Opening</label>
                        <input
                            type="number"
                            min="0"
                            name="stocks[{{ $index }}][opening_stock]"
                            value="{{ old("stocks.$index.opening_stock", $stock->opening_stock ?? 0) }}"
                            class="form-control stock-calc"
                            data-row="{{ $index }}"
                            data-type="opening"
                        >
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small">Production</label>
                        <input
                            type="number"
                            min="0"
                            name="stocks[{{ $index }}][production_qty]"
                            value="{{ old("stocks.$index.production_qty", $stock->production_qty ?? 0) }}"
                            class="form-control stock-calc"
                            data-row="{{ $index }}"
                            data-type="production"
                        >
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small">Sales</label>
                        <input
                            type="number"
                            min="0"
                            name="stocks[{{ $index }}][sales_qty]"
                            value="{{ old("stocks.$index.sales_qty", $stock->sales_qty ?? 0) }}"
                            class="form-control stock-calc"
                            data-row="{{ $index }}"
                            data-type="sales"
                        >
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small">Closing</label>
                        <input
                            type="number"
                            readonly
                            value="{{ old("stocks.$index.closing_stock", $stock->closing_stock ?? 0) }}"
                            class="form-control bg-light"
                            id="closing_{{ $index }}"
                        >
                        <div class="small text-muted">
                            Wastage: {{ $stock->wastage_qty ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                <div>No active products found.</div>
            </div>
        @endforelse

        @if($products->count() > 0)
            <div class="sticky-save">
                <button type="submit" class="btn btn-success w-100 py-3">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Save Daily Stock
                </button>
            </div>
        @endif
    </div>
</form>
@endsection

@push('styles')
<style>
    .stock-item {
        background: #ffffff;
    }

    .stock-item input {
        font-size: 16px;
        min-height: 44px;
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
    function calculateClosing(row) {
        const opening = parseInt(document.querySelector(`[data-row="${row}"][data-type="opening"]`)?.value || 0);
        const production = parseInt(document.querySelector(`[data-row="${row}"][data-type="production"]`)?.value || 0);
        const sales = parseInt(document.querySelector(`[data-row="${row}"][data-type="sales"]`)?.value || 0);

        const closing = opening + production - sales;
        const closingInput = document.getElementById(`closing_${row}`);

        if (closingInput) {
            closingInput.value = closing;
            closingInput.classList.toggle('text-danger', closing < 0);
        }
    }

    document.querySelectorAll('.stock-calc').forEach(function (input) {
        input.addEventListener('input', function () {
            calculateClosing(this.dataset.row);
        });

        calculateClosing(input.dataset.row);
    });
</script>
@endpush