<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutOfStock;
use App\Models\Staff;
use App\Models\Wastage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncentiveController extends Controller
{
    private const OOS_LIMIT = 5;
    private const WASTAGE_COST_LIMIT = 500;

    private const OOS_BONUS_AMOUNT = 100;
    private const WASTAGE_BONUS_AMOUNT = 100;

    public function index(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today('Asia/Kolkata')->toDateString());

        [$startDate, $endDate, $label] = $this->resolveDateRange($type, $date);

        $staffs = Staff::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $rows = $staffs->map(function ($staff) use ($startDate, $endDate) {
            $oosCount = OutOfStock::query()
                ->where('staff_id', $staff->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();

            $wastageQty = Wastage::query()
                ->where('staff_id', $staff->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('quantity');

            $wastageCost = Wastage::query()
                ->where('staff_id', $staff->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('cost_loss');

            $oosBonus = $oosCount <= self::OOS_LIMIT
                ? self::OOS_BONUS_AMOUNT
                : 0;

            $wastageBonus = $wastageCost <= self::WASTAGE_COST_LIMIT
                ? self::WASTAGE_BONUS_AMOUNT
                : 0;

            $totalBonus = $oosBonus + $wastageBonus;

            $status = $totalBonus > 0 ? 'Eligible' : 'Not Eligible';

            return [
                'staff' => $staff,
                'oos_count' => (int) $oosCount,
                'wastage_qty' => (int) $wastageQty,
                'wastage_cost' => (float) $wastageCost,
                'oos_bonus' => $oosBonus,
                'wastage_bonus' => $wastageBonus,
                'total_bonus' => $totalBonus,
                'status' => $status,
                'oos_passed' => $oosCount <= self::OOS_LIMIT,
                'wastage_passed' => $wastageCost <= self::WASTAGE_COST_LIMIT,
            ];
        });

        $summary = [
            'staff_count' => $rows->count(),
            'eligible_staff_count' => $rows->where('total_bonus', '>', 0)->count(),
            'full_bonus_staff_count' => $rows->where('total_bonus', self::OOS_BONUS_AMOUNT + self::WASTAGE_BONUS_AMOUNT)->count(),
            'total_bonus_amount' => $rows->sum('total_bonus'),
            'total_oos_count' => $rows->sum('oos_count'),
            'total_wastage_cost' => $rows->sum('wastage_cost'),
        ];

        return view('admin.incentives.index', compact(
            'type',
            'date',
            'startDate',
            'endDate',
            'label',
            'rows',
            'summary'
        ));
    }

    private function resolveDateRange(string $type, string $date): array
    {
        $carbonDate = Carbon::parse($date);

        if ($type === 'weekly') {
            $startDate = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
            $endDate = $carbonDate->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();

            return [
                $startDate,
                $endDate,
                Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y'),
            ];
        }

        if ($type === 'monthly') {
            $startDate = $carbonDate->copy()->startOfMonth()->toDateString();
            $endDate = $carbonDate->copy()->endOfMonth()->toDateString();

            return [
                $startDate,
                $endDate,
                $carbonDate->format('F Y'),
            ];
        }

        return [
            $carbonDate->toDateString(),
            $carbonDate->toDateString(),
            $carbonDate->format('d M Y'),
        ];
    }
}