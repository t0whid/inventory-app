@extends('layouts.admin')

@section('title', 'Reports')
@section('page_title', 'Reports')
@section('page_subtitle', 'Daily, weekly and monthly inventory business reports')

@section('content')

<div class="page-card mb-4">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label">Report Type</label>
            <select name="type" class="form-select">
                <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ $type === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
        </div>

        <div class="col-12 col-md-4">
            <label class="form-label">Date</label>
            <input type="date" name="date" value="{{ $date }}" class="form-control">
            <div class="form-text">Weekly/monthly report will calculate based on this date.</div>
        </div>

        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fa-solid fa-filter me-1"></i>
                Generate
            </button>

            <a href="{{ route('admin.reports.index') }}" class="btn btn-light flex-fill">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h5 class="mb-1">Report Summary</h5>
        <p class="text-muted mb-0">
            Period: <strong>{{ $label }}</strong>
        </p>
    </div>

    <span class="badge bg-dark">
        {{ ucfirst($type) }} Report
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Stock Entries</p>
            <h4 class="mb-0">{{ $summary['stock_entries'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Production Qty</p>
            <h4 class="mb-0">{{ $summary['total_production'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Sales Qty</p>
            <h4 class="mb-0 text-primary">{{ $summary['total_sales'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Closing Qty</p>
            <h4 class="mb-0 text-success">{{ $summary['total_closing'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Wastage Qty</p>
            <h4 class="mb-0 text-warning">{{ $summary['total_wastage_qty'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Wastage Cost</p>
            <h4 class="mb-0 text-danger">₹{{ number_format($summary['total_wastage_cost'], 2) }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">OOS Items</p>
            <h4 class="mb-0 text-danger">{{ $summary['oos_items'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card report-card">
            <p class="text-muted mb-1">Estimated Profit</p>
            <h4 class="mb-0 {{ $summary['estimated_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                ₹{{ number_format($summary['estimated_profit'], 2) }}
            </h4>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="page-card h-100">
            <h5 class="mb-3">Top Sales Products</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Sales Qty</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($topSalesProducts as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-end fw-semibold text-primary">{{ $item->sales_qty }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No sales data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="page-card h-100">
            <h5 class="mb-3">High Wastage Products</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Cost</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($highWastageProducts as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-end">{{ $item->wastage_qty }}</td>
                                <td class="text-end text-danger fw-semibold">₹{{ number_format($item->wastage_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No wastage data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="page-card mb-4">
    <h5 class="mb-3">Product-wise Report</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle report-table">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th class="text-end">Production</th>
                    <th class="text-end">Sales</th>
                    <th class="text-end">Wastage</th>
                    <th class="text-end">Closing</th>
                    <th class="text-end">Sales Amount</th>
                    <th class="text-end">Gross Profit</th>
                </tr>
            </thead>

            <tbody>
                @forelse($productReports as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->product_name }}</td>
                        <td>{{ $item->category ?: '-' }}</td>
                        <td class="text-end">{{ $item->production_qty }}</td>
                        <td class="text-end text-primary fw-semibold">{{ $item->sales_qty }}</td>
                        <td class="text-end text-warning">{{ $item->wastage_qty }}</td>
                        <td class="text-end text-success">{{ $item->closing_stock }}</td>
                        <td class="text-end">₹{{ number_format($item->sales_amount, 2) }}</td>
                        <td class="text-end text-success fw-semibold">₹{{ number_format($item->gross_profit, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No product report found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="page-card mb-4">
    <h5 class="mb-3">Category-wise Report</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle report-table">
            <thead class="table-light">
                <tr>
                    <th>Category</th>
                    <th class="text-end">Production</th>
                    <th class="text-end">Sales</th>
                    <th class="text-end">Wastage</th>
                    <th class="text-end">Closing</th>
                    <th class="text-end">Sales Amount</th>
                    <th class="text-end">Gross Profit</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categoryReports as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->category ?: 'Uncategorized' }}</td>
                        <td class="text-end">{{ $item->production_qty }}</td>
                        <td class="text-end text-primary fw-semibold">{{ $item->sales_qty }}</td>
                        <td class="text-end text-warning">{{ $item->wastage_qty }}</td>
                        <td class="text-end text-success">{{ $item->closing_stock }}</td>
                        <td class="text-end">₹{{ number_format($item->sales_amount, 2) }}</td>
                        <td class="text-end text-success fw-semibold">₹{{ number_format($item->gross_profit, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No category report found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-6">
        <div class="page-card h-100">
            <h5 class="mb-3">Staff-wise Wastage Report</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Staff</th>
                            <th class="text-end">Entries</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Cost</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($staffWastageReports as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->staff_name }}</div>
                                    <div class="small text-muted">{{ $item->phone ?: '-' }}</div>
                                </td>
                                <td class="text-end">{{ $item->entries }}</td>
                                <td class="text-end">{{ $item->wastage_qty }}</td>
                                <td class="text-end text-danger fw-semibold">₹{{ number_format($item->wastage_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No staff wastage found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="page-card h-100">
            <h5 class="mb-3">Staff-wise OOS Report</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Staff</th>
                            <th class="text-end">OOS Count</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($staffOOSReports as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->staff_name }}</div>
                                    <div class="small text-muted">{{ $item->phone ?: '-' }}</div>
                                </td>
                                <td class="text-end text-danger fw-semibold">{{ $item->oos_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No staff OOS found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .report-card {
        min-height: 112px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .report-card h4 {
        font-size: 24px;
    }

    .report-table {
        min-width: 900px;
    }

    @media (max-width: 575px) {
        .report-card h4 {
            font-size: 20px;
        }
    }
</style>
@endpush