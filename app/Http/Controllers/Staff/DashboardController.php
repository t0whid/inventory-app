<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\OOSSubmission;
use App\Models\OutOfStock;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Wastage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $staffId = session('staff_id');

        $staff = Staff::where('id', $staffId)
            ->where('is_active', true)
            ->first();

        if (!$staff) {
            $request->session()->forget([
                'staff_id',
                'staff_name',
                'staff_phone',
            ]);

            return redirect()
                ->route('staff.login')
                ->with('error', 'Staff session expired. Please login again.');
        }

        $today = Carbon::today('Asia/Kolkata')->toDateString();

        $stockQuery = DailyStock::query()
            ->with('product')
            ->whereDate('date', $today)
            ->where('staff_id', $staffId);

        $summary = [
            'active_products' => Product::where('status', 'active')->count(),

            'stock_entries' => (clone $stockQuery)->count(),
            'total_opening' => (clone $stockQuery)->sum('opening_stock'),
            'total_production' => (clone $stockQuery)->sum('production_qty'),
            'total_sales' => (clone $stockQuery)->sum('sales_qty'),
            'total_wastage_qty' => (clone $stockQuery)->sum('wastage_qty'),
            'total_closing' => (clone $stockQuery)->sum('closing_stock'),

            'wastage_entries' => Wastage::whereDate('date', $today)
                ->where('staff_id', $staffId)
                ->count(),

            'wastage_cost' => Wastage::whereDate('date', $today)
                ->where('staff_id', $staffId)
                ->sum('cost_loss'),

            'oos_count' => OutOfStock::whereDate('date', $today)
                ->where('staff_id', $staffId)
                ->count(),
        ];

        $oosSubmission = OOSSubmission::whereDate('date', $today)
            ->where('staff_id', $staffId)
            ->first();

        $recentStockEntries = DailyStock::with('product')
            ->whereDate('date', $today)
            ->where('staff_id', $staffId)
            ->latest()
            ->take(8)
            ->get();

        $recentWastages = Wastage::with('product')
            ->whereDate('date', $today)
            ->where('staff_id', $staffId)
            ->latest()
            ->take(8)
            ->get();

        $oosItems = OutOfStock::with('product')
            ->whereDate('date', $today)
            ->where('staff_id', $staffId)
            ->latest()
            ->take(10)
            ->get();

        $zeroClosingStocks = DailyStock::with('product')
            ->whereDate('date', $today)
            ->where('staff_id', $staffId)
            ->where('closing_stock', '<=', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('staff.dashboard', compact(
            'staff',
            'today',
            'summary',
            'oosSubmission',
            'recentStockEntries',
            'recentWastages',
            'oosItems',
            'zeroClosingStocks'
        ));
    }
}