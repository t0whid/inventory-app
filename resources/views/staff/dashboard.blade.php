@extends('layouts.staff')

@section('title', 'Staff Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Today stock, wastage and OOS activity')

@section('content')

@php
    $todayFormatted = \Carbon\Carbon::parse($today)->format('d M Y');
@endphp

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h5 class="mb-1">Welcome, {{ $staff->name }}</h5>
        <p class="text-muted mb-0">Today: {{ $todayFormatted }}</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('staff.stock-entry.index', ['date' => $today]) }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            Stock Entry
        </a>

        <a href="{{ route('staff.oos.index', ['date' => $today]) }}" class="btn btn-outline-danger">
            <i class="fa-solid fa-triangle-exclamation me-1"></i>
            Mark OOS
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="page-card staff-card">
            <div class="staff-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <p class="text-muted mb-1">Stock Entries</p>
            <h3 class="mb-1">{{ $summary['stock_entries'] }}</h3>
            <div class="small text-muted">Active products: {{ $summary['active_products'] }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card staff-card">
            <div class="staff-icon bg-success-subtle text-success">
                <i class="fa-solid fa-industry"></i>
            </div>
            <p class="text-muted mb-1">Production Qty</p>
            <h3 class="mb-1">{{ $summary['total_production'] }}</h3>
            <div class="small text-muted">Opening: {{ $summary['total_opening'] }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card staff-card">
            <div class="staff-icon bg-info-subtle text-info">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <p class="text-muted mb-1">Sales Qty</p>
            <h3 class="mb-1 text-primary">{{ $summary['total_sales'] }}</h3>
            <div class="small text-muted">Closing: {{ $summary['total_closing'] }}</div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="page-card staff-card">
            <div class="staff-icon bg-warning-subtle text-warning">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <p class="text-muted mb-1">Wastage</p>
            <h3 class="mb-1">{{ $summary['total_wastage_qty'] }}</h3>
            <div class="small text-muted">Cost: ₹{{ number_format($summary['wastage_cost'], 2) }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="page-card h-100">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <div>
                    <h5 class="mb-1">Today Task Status</h5>
                    <p class="text-muted mb-0">Complete your daily inventory tasks.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="task-box {{ $summary['stock_entries'] > 0 ? 'success' : 'warning' }}">
                        <div class="task-icon">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Stock Entry</div>
                            <div class="fs-5 fw-bold">
                                {{ $summary['stock_entries'] > 0 ? 'Started' : 'Pending' }}
                            </div>
                            <div class="small text-muted">{{ $summary['stock_entries'] }} entries today</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="task-box {{ $oosSubmission ? 'success' : 'danger' }}">
                        <div class="task-icon">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">OOS Submit</div>
                            <div class="fs-5 fw-bold">
                                {{ $oosSubmission ? 'Submitted' : 'Not Submitted' }}
                            </div>
                            <div class="small text-muted">
                                @if($oosSubmission)
                                    {{ \Carbon\Carbon::parse($oosSubmission->submitted_time)->format('h:i A') }}
                                    · {{ $oosSubmission->oos_count }} items
                                @else
                                    Submit before 5:30 PM
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="task-box {{ $zeroClosingStocks->count() > 0 ? 'danger' : 'success' }}">
                        <div class="task-icon">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Stock Alert</div>
                            <div class="fs-5 fw-bold">{{ $zeroClosingStocks->count() }}</div>
                            <div class="small text-muted">Zero/negative closing stock</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($zeroClosingStocks->count())
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
                            @foreach($zeroClosingStocks as $stock)
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
                <a href="{{ route('staff.stock-entry.index', ['date' => $today]) }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-clipboard-list me-2"></i>
                    Daily Stock Entry
                </a>

                <a href="{{ route('staff.wastage.index', ['date' => $today]) }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-trash-can me-2"></i>
                    Wastage Entry
                </a>

                <a href="{{ route('staff.oos.index', ['date' => $today]) }}" class="btn btn-light text-start">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    OOS Entry
                </a>

                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf

                    <button type="submit" class="btn btn-outline-danger w-100 text-start">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="page-card h-100">
            <h5 class="mb-3">Recent Stock Entries</h5>

            <div class="activity-list">
                @forelse($recentStockEntries as $stock)
                    <div class="activity-item">
                        <div class="activity-dot bg-primary"></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                {{ $stock->product->product_name ?? 'Product Deleted' }}
                            </div>
                            <div class="small text-muted">
                                Production {{ $stock->production_qty }}
                                · Sales {{ $stock->sales_qty }}
                                · Closing {{ $stock->closing_stock }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No stock entry found today.
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
                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                {{ $wastage->product->product_name ?? 'Product Deleted' }}
                            </div>
                            <div class="small text-muted">
                                Qty {{ $wastage->quantity }}
                                · {{ ucfirst($wastage->reason) }}
                                · ₹{{ number_format($wastage->cost_loss, 2) }}
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
            <h5 class="mb-3">Today OOS Items</h5>

            <div class="activity-list">
                @forelse($oosItems as $item)
                    <div class="activity-item">
                        <div class="activity-dot bg-danger"></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                {{ $item->product->product_name ?? 'Product Deleted' }}
                            </div>
                            <div class="small text-muted">
                                @if($item->marked_time)
                                    Marked at {{ \Carbon\Carbon::parse($item->marked_time)->format('h:i A') }}
                                @else
                                    Marked today
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No OOS item selected today.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .staff-card {
        min-height: 150px;
    }

    .staff-card h3 {
        font-size: 28px;
        font-weight: 800;
    }

    .staff-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .task-box {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 16px;
        display: flex;
        gap: 14px;
        height: 100%;
        background: #ffffff;
    }

    .task-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        flex-shrink: 0;
    }

    .task-box.success {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .task-box.success .task-icon {
        background: #16a34a;
        color: #ffffff;
    }

    .task-box.warning {
        background: #fffbeb;
        border-color: #fde68a;
    }

    .task-box.warning .task-icon {
        background: #f59e0b;
        color: #ffffff;
    }

    .task-box.danger {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .task-box.danger .task-icon {
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
        .staff-card {
            min-height: 135px;
        }

        .staff-card h3 {
            font-size: 22px;
        }
    }
</style>
@endpush