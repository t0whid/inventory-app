<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\OOSSubmission;
use App\Models\OutOfStock;
use App\Models\Product;
use App\Models\Staff;
use App\Models\TelegramLog;
use App\Models\TelegramSetting;
use App\Models\Wastage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Kolkata')->toDateString();
        $yesterday = Carbon::yesterday('Asia/Kolkata')->toDateString();

        $todayStockQuery = DailyStock::query()
            ->whereDate('date', $today);

        $summary = [
            'active_products' => Product::where('status', 'active')->count(),
            'active_staff' => Staff::where('is_active', true)->count(),

            'stock_entries' => (clone $todayStockQuery)->count(),
            'total_opening' => (clone $todayStockQuery)->sum('opening_stock'),
            'total_production' => (clone $todayStockQuery)->sum('production_qty'),
            'total_sales' => (clone $todayStockQuery)->sum('sales_qty'),
            'total_wastage_qty' => (clone $todayStockQuery)->sum('wastage_qty'),
            'total_closing' => (clone $todayStockQuery)->sum('closing_stock'),

            'today_wastage_cost' => Wastage::whereDate('date', $today)->sum('cost_loss'),
            'today_oos_count' => OutOfStock::whereDate('date', $today)->count(),

            'yesterday_sales' => DailyStock::whereDate('date', $yesterday)->sum('sales_qty'),
            'yesterday_wastage_cost' => Wastage::whereDate('date', $yesterday)->sum('cost_loss'),
            'yesterday_oos_count' => OutOfStock::whereDate('date', $yesterday)->count(),
        ];

        $submittedStaffCount = OOSSubmission::whereDate('date', $today)->count();
        $activeStaffCount = $summary['active_staff'];

        $summary['oos_submitted_staff'] = $submittedStaffCount;
        $summary['oos_missing_staff'] = max(0, $activeStaffCount - $submittedStaffCount);

        $salesAmount = DailyStock::query()
            ->join('products', 'products.id', '=', 'daily_stocks.product_id')
            ->whereDate('daily_stocks.date', $today)
            ->selectRaw('
                COALESCE(SUM(daily_stocks.sales_qty * products.selling_price), 0) as sales_amount,
                COALESCE(SUM(daily_stocks.sales_qty * products.cost_price), 0) as cost_amount
            ')
            ->first();

        $summary['sales_amount'] = (float) ($salesAmount->sales_amount ?? 0);
        $summary['cost_amount'] = (float) ($salesAmount->cost_amount ?? 0);
        $summary['estimated_profit'] = $summary['sales_amount']
            - $summary['cost_amount']
            - (float) $summary['today_wastage_cost'];

        $topSalesProducts = DailyStock::query()
            ->join('products', 'products.id', '=', 'daily_stocks.product_id')
            ->whereDate('daily_stocks.date', $today)
            ->where('daily_stocks.sales_qty', '>', 0)
            ->groupBy('products.id', 'products.product_name', 'products.category')
            ->selectRaw('
                products.id,
                products.product_name,
                products.category,
                COALESCE(SUM(daily_stocks.sales_qty), 0) as sales_qty
            ')
            ->orderByDesc('sales_qty')
            ->take(5)
            ->get();

        $highWastageProducts = Wastage::query()
            ->join('products', 'products.id', '=', 'wastages.product_id')
            ->whereDate('wastages.date', $today)
            ->groupBy('products.id', 'products.product_name', 'products.category')
            ->selectRaw('
                products.id,
                products.product_name,
                products.category,
                COALESCE(SUM(wastages.quantity), 0) as wastage_qty,
                COALESCE(SUM(wastages.cost_loss), 0) as wastage_cost
            ')
            ->orderByDesc('wastage_cost')
            ->take(5)
            ->get();

        $oosItems = OutOfStock::query()
            ->with(['product', 'staff'])
            ->whereDate('date', $today)
            ->latest()
            ->take(8)
            ->get();

        $missingStaffs = Staff::query()
            ->where('is_active', true)
            ->whereNotIn('id', function ($query) use ($today) {
                $query->select('staff_id')
                    ->from('oos_submissions')
                    ->whereDate('date', $today);
            })
            ->orderBy('name')
            ->take(8)
            ->get();

        $zeroClosingProducts = DailyStock::query()
            ->with('product')
            ->whereDate('date', $today)
            ->where('closing_stock', '<=', 0)
            ->orderBy('closing_stock')
            ->take(8)
            ->get();

        $recentWastages = Wastage::query()
            ->with(['product', 'staff'])
            ->whereDate('date', $today)
            ->latest()
            ->take(8)
            ->get();

        $recentTelegramLogs = TelegramLog::query()
            ->latest()
            ->take(6)
            ->get();

        $telegramSetting = TelegramSetting::current();

        return view('admin.dashboard', compact(
            'today',
            'summary',
            'topSalesProducts',
            'highWastageProducts',
            'oosItems',
            'missingStaffs',
            'zeroClosingProducts',
            'recentWastages',
            'recentTelegramLogs',
            'telegramSetting'
        ));
    }
}