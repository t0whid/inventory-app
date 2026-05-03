@extends('layouts.staff')

@section('title', 'Wastage Entry')
@section('page_title', 'Wastage Entry')

@section('content')
<div class="page-card mb-3">
    <form method="GET" action="{{ route('staff.wastage.index') }}">
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

<div class="page-card mb-3">
    <div class="mb-3">
        <h5 class="mb-1">Add Wastage</h5>
        <p class="text-muted mb-0 small">
            Select product, enter quantity and reason.
        </p>
    </div>

    <form action="{{ route('staff.wastage.store') }}" method="POST">
        @csrf

        <input type="hidden" name="date" value="{{ $date }}">

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Product <span class="text-danger">*</span></label>
                <select name="product_id" id="productSelect" class="form-select">
                    <option value="">Select Product</option>

                    @foreach($products as $product)
                        <option
                            value="{{ $product->id }}"
                            data-cost="{{ $product->cost_price }}"
                            {{ old('product_id') == $product->id ? 'selected' : '' }}
                        >
                            {{ $product->product_name }} {{ $product->category ? '('.$product->category.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input
                    type="number"
                    min="1"
                    name="quantity"
                    id="quantityInput"
                    value="{{ old('quantity') }}"
                    class="form-control"
                    placeholder="Enter wastage qty"
                >
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Reason <span class="text-danger">*</span></label>
                <select name="reason" class="form-select">
                    <option value="">Select Reason</option>
                    <option value="expired" {{ old('reason') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="damaged" {{ old('reason') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="unsold" {{ old('reason') === 'unsold' ? 'selected' : '' }}>Unsold</option>
                </select>
            </div>

            <div class="col-12">
                <div class="cost-preview">
                    <div>
                        <span class="text-muted">Estimated Cost Loss</span>
                        <h5 class="mb-0" id="costLossPreview">₹0.00</h5>
                    </div>

                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 py-3 mt-4">
            <i class="fa-solid fa-floppy-disk me-1"></i>
            Save Wastage
        </button>
    </form>
</div>

<div class="page-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">Today Wastage List</h5>
            <p class="text-muted mb-0 small">
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </p>
        </div>

        <span class="badge bg-danger">
            {{ $wastages->count() }} entries
        </span>
    </div>

    @forelse($wastages as $wastage)
        <div class="wastage-item border rounded-3 p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h6 class="mb-1">{{ $wastage->product->product_name ?? 'Product Deleted' }}</h6>

                    <div class="small text-muted">
                        Qty: {{ $wastage->quantity }}
                        |
                        Reason: {{ ucfirst($wastage->reason) }}
                    </div>

                    <div class="small fw-semibold text-danger mt-1">
                        Cost Loss: ₹{{ number_format($wastage->cost_loss, 2) }}
                    </div>
                </div>

                <form action="{{ route('staff.wastage.destroy', $wastage) }}" method="POST"
                      onsubmit="return confirm('Delete this wastage entry?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-trash-can fa-2x mb-2"></i>
            <div>No wastage entry found for this date.</div>
        </div>
    @endforelse
</div>
@endsection

@push('styles')
<style>
    .cost-preview {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cost-preview i {
        color: #f97316;
        font-size: 26px;
    }

    .wastage-item {
        background: #ffffff;
    }

    input,
    select {
        min-height: 44px;
        font-size: 16px;
    }
</style>
@endpush

@push('scripts')
<script>
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const costLossPreview = document.getElementById('costLossPreview');

    function calculateCostLoss() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const cost = parseFloat(selectedOption?.dataset?.cost || 0);
        const qty = parseInt(quantityInput.value || 0);

        const total = cost * qty;

        costLossPreview.innerText = '₹' + total.toFixed(2);
    }

    productSelect?.addEventListener('change', calculateCostLoss);
    quantityInput?.addEventListener('input', calculateCostLoss);

    calculateCostLoss();
</script>
@endpush