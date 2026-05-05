@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Today inventory overview, alerts and staff activity')

@section('content')

@php
    $todayFormatted = \Carbon\Carbon::parse($today)->format('d M Y');
@endphp

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h5 class="mb-1">Today Overview</h5>
        <p class="text-muted mb-0">Showing business status for {{ $todayFormatted }}</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.daily-stocks.index', ['date' => $today]) }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-boxes-stacked me-1"></i>
            Daily Stocks
        </a>

        <a href="{{ route('admin.reports.index', ['type' => 'daily', 'date' => $today]) }}" class="btn btn-primary">
            <i class="fa-solid fa-chart-line me-1"></i>
            View Report
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <p class="text-muted mb-1">Sales Qty</p>
            <h3 class="mb-1">{{ $summary['total_sales'] }}</h3>
            <div class="small text-muted">Yesterday: {{ $summary['yesterday_sales'] }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-success-subtle text-success">
                <i class="fa-solid fa-industry"></i>
            </div>
            <p class="text-muted mb-1">Production Qty</p>
            <h3 class="mb-1">{{ $summary['total_production'] }}</h3>
            <div class="small text-muted">Entries: {{ $summary['stock_entries'] }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-danger-subtle text-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <p class="text-muted mb-1">OOS Items</p>
            <h3 class="mb-1">{{ $summary['today_oos_count'] }}</h3>
            <div class="small text-muted">Yesterday: {{ $summary['yesterday_oos_count'] }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-warning-subtle text-warning">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <p class="text-muted mb-1">Wastage Cost</p>
            <h3 class="mb-1">₹{{ number_format($summary['today_wastage_cost'], 2) }}</h3>
            <div class="small text-muted">Yesterday: ₹{{ number_format($summary['yesterday_wastage_cost'], 2) }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-info-subtle text-info">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <p class="text-muted mb-1">Sales Amount</p>
            <h3 class="mb-1">₹{{ number_format($summary['sales_amount'], 2) }}</h3>
            <div class="small text-muted">Cost: ₹{{ number_format($summary['cost_amount'], 2) }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-success-subtle text-success">
                <i class="fa-solid fa-coins"></i>
            </div>
            <p class="text-muted mb-1">Estimated Profit</p>
            <h3 class="mb-1 {{ $summary['estimated_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                ₹{{ number_format($summary['estimated_profit'], 2) }}
            </h3>
            <div class="small text-muted">After wastage cost</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-secondary-subtle text-secondary">
                <i class="fa-solid fa-users"></i>
            </div>
            <p class="text-muted mb-1">Active Staff</p>
            <h3 class="mb-1">{{ $summary['active_staff'] }}</h3>
            <div class="small text-muted">
                OOS submitted: {{ $summary['oos_submitted_staff'] }}/{{ $summary['active_staff'] }}
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card dash-card">
            <div class="dash-icon bg-dark-subtle text-dark">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <p class="text-muted mb-1">Active Products</p>
            <h3 class="mb-1">{{ $summary['active_products'] }}</h3>
            <div class="small text-muted">Closing qty: {{ $summary['total_closing'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="page-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Today Alerts</h5>
                    <p class="text-muted mb-0">Important items that need attention.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="alert-box {{ $summary['oos_missing_staff'] > 0 ? 'danger' : 'success' }}">
                        <div class="alert-icon">
                            <i class="fa-solid fa-user-clock"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">OOS Missing Staff</div>
                            <div class="fs-4 fw-bold">{{ $summary['oos_missing_staff'] }}</div>
                            <div class="small text-muted">Staff did not submit OOS yet</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="alert-box {{ $zeroClosingProducts->count() > 0 ? 'danger' : 'success' }}">
                        <div class="alert-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Zero/Negative Stock</div>
                            <div class="fs-4 fw-bold">{{ $zeroClosingProducts->count() }}</div>
                            <div class="small text-muted">Products need stock review</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="alert-box {{ $telegramSetting->isReady() ? 'success' : 'warning' }}">
                        <div class="alert-icon">
                            <i class="fa-brands fa-telegram"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Telegram Status</div>
                            <div class="fs-6 fw-bold">
                                {{ $telegramSetting->isReady() ? 'Ready' : 'Not Ready' }}
                            </div>
                            <div class="small text-muted">Automatic alert system</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($missingStaffs->count())
                <hr>
                <h6 class="mb-3">Missing OOS Submission</h6>

                <div class="d-flex flex-wrap gap-2">
                    @foreach($missingStaffs as $staff)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                            {{ $staff->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            @if($zeroClosingProducts->count())
                <hr>
                <h6 class="mb-3">Zero / Negative Closing Stock</h6>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Closing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($zeroClosingProducts as $stock)
                                <tr>
                                    <td>{{ $stock->product->product_name ?? 'Product Deleted' }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-danger">{{ $stock->closing_stock }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="page-card h-100">
            <h5 class="mb-3">Quick Actions</h5>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-box me-2"></i>
                    Manage Products
                </a>

                <a href="{{ route('admin.staffs.index') }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-users me-2"></i>
                    Manage Staff
                </a>

                <a href="{{ route('admin.daily-stocks.index', ['date' => $today]) }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-clipboard-list me-2"></i>
                    View Daily Stocks
                </a>

                <a href="{{ route('admin.reports.index', ['type' => 'daily', 'date' => $today]) }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    Reports
                </a>

                <a href="{{ route('admin.incentives.index', ['type' => 'daily', 'date' => $today]) }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-gift me-2"></i>
                    Incentives
                </a>

                <a href="{{ route('admin.telegram-settings.index') }}" class="btn btn-light text-start">
                    <i class="fa-brands fa-telegram me-2"></i>
                    Telegram Settings
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="page-card h-100">
            <h5 class="mb-3">Top Sales Products</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-end">Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSalesProducts as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->product_name }}</td>
                                <td>{{ $item->category ?: '-' }}</td>
                                <td class="text-end text-primary fw-bold">{{ $item->sales_qty }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    No sales data found today.
                                </td>
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
                <table class="table table-hover align-middle mb-0">
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
                                <td class="fw-semibold">{{ $item->product_name }}</td>
                                <td class="text-end">{{ $item->wastage_qty }}</td>
                                <td class="text-end text-danger fw-bold">₹{{ number_format($item->wastage_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    No wastage data found today.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="page-card h-100">
            <h5 class="mb-3">Recent OOS Items</h5>

            <div class="activity-list">
                @forelse($oosItems as $item)
                    <div class="activity-item">
                        <div class="activity-dot bg-danger"></div>
                        <div>
                            <div class="fw-semibold">{{ $item->product->product_name ?? 'Product Deleted' }}</div>
                            <div class="small text-muted">
                                By {{ $item->staff->name ?? '-' }}
                                @if($item->marked_time)
                                    · {{ \Carbon\Carbon::parse($item->marked_time)->format('h:i A') }}
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No OOS item found today.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="page-card h-100">
            <h5 class="mb-3">Recent Wastage</h5>

            <div class="activity-list">
                @forelse($recentWastages as $wastage)
                    <div class="activity-item">
                        <div class="activity-dot bg-warning"></div>
                        <div>
                            <div class="fw-semibold">{{ $wastage->product->product_name ?? 'Product Deleted' }}</div>
                            <div class="small text-muted">
                                Qty {{ $wastage->quantity }}
                                · ₹{{ number_format($wastage->cost_loss, 2) }}
                                · {{ $wastage->staff->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No wastage found today.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="page-card h-100">
            <h5 class="mb-3">Telegram Logs</h5>

            <div class="activity-list">
                @forelse($recentTelegramLogs as $log)
                    <div class="activity-item">
                        <div class="activity-dot {{ $log->status === 'success' ? 'bg-success' : ($log->status === 'failed' ? 'bg-danger' : 'bg-secondary') }}"></div>
                        <div>
                            <div class="fw-semibold">
                                {{ ucfirst(str_replace('_', ' ', $log->type)) }}
                            </div>
                            <div class="small text-muted">
                                {{ ucfirst($log->status) }}
                                · {{ $log->created_at->format('d M, h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No Telegram log found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .dash-card {
        min-height: 150px;
        position: relative;
        overflow: hidden;
    }

    .dash-card h3 {
        font-size: 28px;
        font-weight: 800;
    }

    .dash-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .alert-box {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 16px;
        display: flex;
        gap: 14px;
        height: 100%;
        background: #ffffff;
    }

    .alert-box .alert-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        flex-shrink: 0;
    }

    .alert-box.success {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .alert-box.success .alert-icon {
        background: #16a34a;
        color: #ffffff;
    }

    .alert-box.warning {
        background: #fffbeb;
        border-color: #fde68a;
    }

    .alert-box.warning .alert-icon {
        background: #f59e0b;
        color: #ffffff;
    }

    .alert-box.danger {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .alert-box.danger .alert-icon {
        background: #dc2626;
        color: #ffffff;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .activity-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding-bottom: 12px;
        border-bottom: 1px solid #f3f4f6;
    }

    .activity-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .activity-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        margin-top: 7px;
        flex-shrink: 0;
    }

    @media (max-width: 575px) {
        .dash-card {
            min-height: 135px;
        }

        .dash-card h3 {
            font-size: 22px;
        }
    }
</style>
@endpush