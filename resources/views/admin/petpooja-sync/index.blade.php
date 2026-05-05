@extends('layouts.admin')

@section('title', 'Petpooja Sync')
@section('page_title', 'Petpooja Sync')
@section('page_subtitle', 'Fetch Petpooja sales and update daily stock sales quantity')

@section('content')
<div class="page-card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="mb-1">Fetch & Sync Sales</h5>
            <p class="text-muted mb-0">
                Select actual sales date. System will send next date to Petpooja API.
            </p>
        </div>
    </div>

    <form action="{{ route('admin.petpooja-sync.sync') }}" method="POST">
        @csrf

        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <label class="form-label">Actual Sales Date</label>
                <input
                    type="date"
                    name="sales_date"
                    value="{{ old('sales_date', $salesDate) }}"
                    class="form-control"
                >
                <div class="form-text">
                    Example: choose 2026-02-25, API will request 2026-02-26.
                </div>
            </div>

            <div class="col-12 col-md-6">
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fa-solid fa-rotate me-1"></i>
                    Fetch & Sync Sales
                </button>
            </div>
        </div>
    </form>
</div>

@if($report)
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="page-card">
                <p class="text-muted mb-1">Sales Date</p>
                <h5 class="mb-0">{{ $report['sales_date'] }}</h5>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="page-card">
                <p class="text-muted mb-1">API Order Date</p>
                <h5 class="mb-0">{{ $report['petpooja_order_date'] }}</h5>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="page-card">
                <p class="text-muted mb-1">Matched</p>
                <h5 class="mb-0 text-success">{{ $report['matched_count'] }}</h5>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="page-card">
                <p class="text-muted mb-1">Unmatched</p>
                <h5 class="mb-0 text-danger">{{ $report['unmatched_count'] }}</h5>
            </div>
        </div>
    </div>

    <div class="page-card mb-4">
        <h5 class="mb-3">Matched Items</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">SL</th>
                        <th>Product</th>
                        <th>Sales Qty</th>
                        <th>Sales Amount</th>
                        <th>Closing Stock</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($report['matched'] as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['product_name'] }}</td>
                            <td>{{ $item['sales_qty'] }}</td>
                            <td>₹{{ number_format($item['sales_amount'], 2) }}</td>
                            <td>
                                @if($item['closing_stock'] < 0)
                                    <span class="badge bg-danger">{{ $item['closing_stock'] }}</span>
                                @else
                                    <span class="badge bg-success">{{ $item['closing_stock'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No matched item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="page-card">
        <h5 class="mb-3">Unmatched Petpooja Items</h5>

        @if(count($report['unmatched']) > 0)
            <div class="alert alert-warning">
                These item names do not match with products table. Create products with same name or rename product.
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">SL</th>
                        <th>Petpooja Item Name</th>
                        <th>Qty</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($report['unmatched'] as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>₹{{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No unmatched item. Great!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection