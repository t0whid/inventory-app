@extends('layouts.admin')

@section('title', 'Incentives')
@section('page_title', 'Incentives')
@section('page_subtitle', 'Staff bonus calculation based on OOS and wastage performance')

@section('content')

<div class="page-card mb-4">
    <form method="GET" action="{{ route('admin.incentives.index') }}" class="row g-3 align-items-end">
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
            <div class="form-text">Weekly/monthly incentive will calculate based on this date.</div>
        </div>

        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="fa-solid fa-filter me-1"></i>
                Generate
            </button>

            <a href="{{ route('admin.incentives.index') }}" class="btn btn-light flex-fill">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h5 class="mb-1">Incentive Summary</h5>
        <p class="text-muted mb-0">
            Period: <strong>{{ $label }}</strong>
        </p>
    </div>

    <span class="badge bg-dark">
        {{ ucfirst($type) }} Incentive
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="page-card incentive-card">
            <p class="text-muted mb-1">Active Staff</p>
            <h4 class="mb-0">{{ $summary['staff_count'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card incentive-card">
            <p class="text-muted mb-1">Eligible Staff</p>
            <h4 class="mb-0 text-success">{{ $summary['eligible_staff_count'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card incentive-card">
            <p class="text-muted mb-1">Full Bonus Staff</p>
            <h4 class="mb-0 text-primary">{{ $summary['full_bonus_staff_count'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card incentive-card">
            <p class="text-muted mb-1">Total Bonus</p>
            <h4 class="mb-0 text-success">₹{{ number_format($summary['total_bonus_amount'], 2) }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card incentive-card">
            <p class="text-muted mb-1">Total OOS</p>
            <h4 class="mb-0 text-danger">{{ $summary['total_oos_count'] }}</h4>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="page-card incentive-card">
            <p class="text-muted mb-1">Wastage Cost</p>
            <h4 class="mb-0 text-danger">₹{{ number_format($summary['total_wastage_cost'], 2) }}</h4>
        </div>
    </div>
</div>

<div class="page-card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h5 class="mb-1">Bonus Rules</h5>
            <p class="text-muted mb-0">Current simple rule for incentive calculation.</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <div class="rule-box">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-circle-check text-success"></i>
                    <strong>OOS Bonus</strong>
                </div>
                <div class="text-muted">
                    If staff OOS count is <strong>5 or less</strong>, bonus is <strong>₹100</strong>.
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="rule-box">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-circle-check text-success"></i>
                    <strong>Wastage Bonus</strong>
                </div>
                <div class="text-muted">
                    If staff wastage cost is <strong>₹500 or less</strong>, bonus is <strong>₹100</strong>.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-card">
    <h5 class="mb-3">Staff-wise Incentive Report</h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle incentive-table">
            <thead class="table-light">
                <tr>
                    <th>Staff</th>
                    <th class="text-end">OOS Count</th>
                    <th class="text-end">Wastage Qty</th>
                    <th class="text-end">Wastage Cost</th>
                    <th class="text-end">OOS Bonus</th>
                    <th class="text-end">Wastage Bonus</th>
                    <th class="text-end">Total Bonus</th>
                    <th class="text-end">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $row['staff']->name }}</div>
                            <div class="small text-muted">
                                {{ $row['staff']->phone ?: '-' }}
                            </div>
                        </td>

                        <td class="text-end">
                            <span class="badge {{ $row['oos_passed'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $row['oos_count'] }}
                            </span>
                        </td>

                        <td class="text-end">
                            {{ $row['wastage_qty'] }}
                        </td>

                        <td class="text-end">
                            <span class="{{ $row['wastage_passed'] ? 'text-success' : 'text-danger' }} fw-semibold">
                                ₹{{ number_format($row['wastage_cost'], 2) }}
                            </span>
                        </td>

                        <td class="text-end">
                            ₹{{ number_format($row['oos_bonus'], 2) }}
                        </td>

                        <td class="text-end">
                            ₹{{ number_format($row['wastage_bonus'], 2) }}
                        </td>

                        <td class="text-end">
                            <strong class="{{ $row['total_bonus'] > 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($row['total_bonus'], 2) }}
                            </strong>
                        </td>

                        <td class="text-end">
                            @if($row['total_bonus'] === 200)
                                <span class="badge bg-success">Full Bonus</span>
                            @elseif($row['total_bonus'] > 0)
                                <span class="badge bg-warning text-dark">Partial Bonus</span>
                            @else
                                <span class="badge bg-danger">Not Eligible</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fa-solid fa-user-slash fa-2x mb-2"></i>
                            <div>No active staff found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if($rows->count())
                <tfoot class="table-light">
                    <tr>
                        <th>Total</th>
                        <th class="text-end">{{ $rows->sum('oos_count') }}</th>
                        <th class="text-end">{{ $rows->sum('wastage_qty') }}</th>
                        <th class="text-end">₹{{ number_format($rows->sum('wastage_cost'), 2) }}</th>
                        <th class="text-end">₹{{ number_format($rows->sum('oos_bonus'), 2) }}</th>
                        <th class="text-end">₹{{ number_format($rows->sum('wastage_bonus'), 2) }}</th>
                        <th class="text-end text-success">₹{{ number_format($rows->sum('total_bonus'), 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection

@push('styles')
<style>
    .incentive-card {
        min-height: 112px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .incentive-card h4 {
        font-size: 24px;
    }

    .rule-box {
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #f9fafb;
        height: 100%;
    }

    .incentive-table {
        min-width: 950px;
    }

    @media (max-width: 575px) {
        .incentive-card h4 {
            font-size: 20px;
        }
    }
</style>
@endpush