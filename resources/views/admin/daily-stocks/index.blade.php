@extends('layouts.admin')

@section('title', 'Daily Stocks')
@section('page_title', 'Daily Stocks')
@section('page_subtitle', 'View daily opening, production, sales, wastage and closing stock')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="page-card summary-card">
            <p class="text-muted mb-1">Products</p>
            <h4 class="mb-0">{{ $summary['total_products'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-2">
        <div class="page-card summary-card">
            <p class="text-muted mb-1">Opening</p>
            <h4 class="mb-0">{{ $summary['total_opening'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-2">
        <div class="page-card summary-card">
            <p class="text-muted mb-1">Production</p>
            <h4 class="mb-0">{{ $summary['total_production'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-2">
        <div class="page-card summary-card">
            <p class="text-muted mb-1">Sales</p>
            <h4 class="mb-0 text-primary">{{ $summary['total_sales'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-2">
        <div class="page-card summary-card">
            <p class="text-muted mb-1">Wastage</p>
            <h4 class="mb-0 text-warning">{{ $summary['total_wastage'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-2">
        <div class="page-card summary-card">
            <p class="text-muted mb-1">Closing</p>
            <h4 class="mb-0 text-success">{{ $summary['total_closing'] }}</h4>
        </div>
    </div>
</div>

<div class="page-card mb-4">
    <form method="GET" action="{{ route('admin.daily-stocks.index') }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label">Date</label>
            <input type="date" name="date" value="{{ $date }}" class="form-control">
        </div>

        <div class="col-12 col-md-4">
            <label class="form-label">Product</label>
            <select name="product_id" class="form-select">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-4">
            <label class="form-label">Stock Status</label>
            <select name="stock_status" class="form-select">
                <option value="">All</option>
                <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="negative" {{ request('stock_status') === 'negative' ? 'selected' : '' }}>Negative Stock</option>
            </select>
        </div>

        <div class="col-12 d-flex flex-column flex-md-row gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter me-1"></i>
                Filter
            </button>

            <a href="{{ route('admin.daily-stocks.index') }}" class="btn btn-light">
                Reset
            </a>

            <a href="{{ route('admin.petpooja-sync.index') }}" class="btn btn-outline-dark ms-md-auto">
                <i class="fa-solid fa-plug me-1"></i>
                Petpooja Sync
            </a>
        </div>
    </form>
</div>

<div class="page-card">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="mb-1">Daily Stock List</h5>
            <p class="text-muted mb-0">
                Showing stock entries for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </p>
        </div>

        <span class="badge bg-primary">
            {{ $dailyStocks->total() }} records
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">SL</th>
                    <th>Product</th>
                    <th>Opening</th>
                    <th>Production</th>
                    <th>Sales</th>
                    <th>Wastage</th>
                    <th>Closing</th>
                    <th>Reorder</th>
                    <th>Staff</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dailyStocks as $stock)
                    @php
                        $isLowStock = $stock->product && $stock->closing_stock < $stock->product->reorder_level;
                    @endphp

                    <tr>
                        <td>
                            {{ $dailyStocks->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $stock->product->product_name ?? 'Product Deleted' }}
                            </div>

                            <div class="small text-muted">
                                {{ $stock->product->category ?? '-' }}
                            </div>
                        </td>

                        <td>{{ $stock->opening_stock }}</td>

                        <td>{{ $stock->production_qty }}</td>

                        <td>
                            <span class="fw-semibold text-primary">
                                {{ $stock->sales_qty }}
                            </span>
                        </td>

                        <td>
                            <span class="fw-semibold text-warning">
                                {{ $stock->wastage_qty }}
                            </span>
                        </td>

                        <td>
                            @if($stock->closing_stock < 0)
                                <span class="badge bg-danger">
                                    {{ $stock->closing_stock }}
                                </span>
                            @elseif($isLowStock)
                                <span class="badge bg-warning text-dark">
                                    {{ $stock->closing_stock }}
                                </span>
                            @else
                                <span class="badge bg-success">
                                    {{ $stock->closing_stock }}
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $stock->product->reorder_level ?? '-' }}
                        </td>

                        <td>
                            {{ $stock->staff->name ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fa-solid fa-clipboard-list fa-2x mb-2"></i>
                            <div>No daily stock found for this date.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $dailyStocks->links() }}
    </div>
</div>

@endsection

@push('styles')
<style>
    .summary-card {
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (max-width: 575px) {
        .summary-card h4 {
            font-size: 20px;
        }
    }
</style>
@endpush