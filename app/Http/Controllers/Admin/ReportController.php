<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\OutOfStock;
use App\Models\Staff;
use App\Models\Wastage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today('Asia/Kolkata')->toDateString());

        [$startDate, $endDate, $label] = $this->resolveDateRange($type, $date);

        $summary = $this->getSummary($startDate, $endDate);
        $productReports = $this->getProductReports($startDate, $endDate);
        $categoryReports = $this->getCategoryReports($startDate, $endDate);
        $staffWastageReports = $this->getStaffWastageReports($startDate, $endDate);
        $staffOOSReports = $this->getStaffOOSReports($startDate, $endDate);
        $topSalesProducts = $this->getTopSalesProducts($startDate, $endDate);
        $highWastageProducts = $this->getHighWastageProducts($startDate, $endDate);

        return view('admin.reports.index', compact(
            'type',
            'date',
            'startDate',
            'endDate',
            'label',
            'summary',
            'productReports',
            'categoryReports',
            'staffWastageReports',
            'staffOOSReports',
            'topSalesProducts',
            'highWastageProducts'
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

    private function getSummary(string $startDate, string $endDate): array
    {
        $stock = DailyStock::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as stock_entries,
                COALESCE(SUM(opening_stock), 0) as total_opening,
                COALESCE(SUM(production_qty), 0) as total_production,
                COALESCE(SUM(sales_qty), 0) as total_sales,
                COALESCE(SUM(wastage_qty), 0) as total_wastage_qty,
                COALESCE(SUM(closing_stock), 0) as total_closing
            ')
            ->first();

        $wastageCost = Wastage::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('cost_loss');

        $oosItems = OutOfStock::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $profitEstimate = DailyStock::query()
            ->join('products', 'products.id', '=', 'daily_stocks.product_id')
            ->whereBetween('daily_stocks.date', [$startDate, $endDate])
            ->selectRaw('
                COALESCE(SUM(daily_stocks.sales_qty * products.selling_price), 0) as total_sales_amount,
                COALESCE(SUM(daily_stocks.sales_qty * products.cost_price), 0) as total_cost_amount
            ')
            ->first();

        $totalSalesAmount = (float) ($profitEstimate->total_sales_amount ?? 0);
        $totalCostAmount = (float) ($profitEstimate->total_cost_amount ?? 0);
        $totalWastageCost = (float) $wastageCost;

        return [
            'stock_entries' => (int) ($stock->stock_entries ?? 0),
            'total_opening' => (int) ($stock->total_opening ?? 0),
            'total_production' => (int) ($stock->total_production ?? 0),
            'total_sales' => (int) ($stock->total_sales ?? 0),
            'total_wastage_qty' => (int) ($stock->total_wastage_qty ?? 0),
            'total_closing' => (int) ($stock->total_closing ?? 0),
            'total_wastage_cost' => $totalWastageCost,
            'oos_items' => (int) $oosItems,
            'total_sales_amount' => $totalSalesAmount,
            'total_cost_amount' => $totalCostAmount,
            'estimated_profit' => $totalSalesAmount - $totalCostAmount - $totalWastageCost,
        ];
    }

    private function getProductReports(string $startDate, string $endDate)
    {
        return DailyStock::query()
            ->join('products', 'products.id', '=', 'daily_stocks.product_id')
            ->whereBetween('daily_stocks.date', [$startDate, $endDate])
            ->groupBy(
                'products.id',
                'products.product_name',
                'products.category',
                'products.cost_price',
                'products.selling_price'
            )
            ->selectRaw('
                products.id,
                products.product_name,
                products.category,
                products.cost_price,
                products.selling_price,
                COALESCE(SUM(daily_stocks.production_qty), 0) as production_qty,
                COALESCE(SUM(daily_stocks.sales_qty), 0) as sales_qty,
                COALESCE(SUM(daily_stocks.wastage_qty), 0) as wastage_qty,
                COALESCE(SUM(daily_stocks.closing_stock), 0) as closing_stock,
                COALESCE(SUM(daily_stocks.sales_qty * products.selling_price), 0) as sales_amount,
                COALESCE(SUM(daily_stocks.sales_qty * products.cost_price), 0) as cost_amount,
                COALESCE(SUM(daily_stocks.sales_qty * (products.selling_price - products.cost_price)), 0) as gross_profit
            ')
            ->orderByDesc('sales_qty')
            ->get();
    }

    private function getCategoryReports(string $startDate, string $endDate)
    {
        return DailyStock::query()
            ->join('products', 'products.id', '=', 'daily_stocks.product_id')
            ->whereBetween('daily_stocks.date', [$startDate, $endDate])
            ->groupBy('products.category')
            ->selectRaw('
                COALESCE(products.category, "Uncategorized") as category,
                COALESCE(SUM(daily_stocks.production_qty), 0) as production_qty,
                COALESCE(SUM(daily_stocks.sales_qty), 0) as sales_qty,
                COALESCE(SUM(daily_stocks.wastage_qty), 0) as wastage_qty,
                COALESCE(SUM(daily_stocks.closing_stock), 0) as closing_stock,
                COALESCE(SUM(daily_stocks.sales_qty * products.selling_price), 0) as sales_amount,
                COALESCE(SUM(daily_stocks.sales_qty * (products.selling_price - products.cost_price)), 0) as gross_profit
            ')
            ->orderByDesc('sales_qty')
            ->get();
    }

    private function getStaffWastageReports(string $startDate, string $endDate)
    {
        return Wastage::query()
            ->leftJoin('staff', 'staff.id', '=', 'wastages.staff_id')
            ->whereBetween('wastages.date', [$startDate, $endDate])
            ->groupBy('staff.id', 'staff.name', 'staff.phone')
            ->selectRaw('
                staff.id,
                COALESCE(staff.name, "Unknown Staff") as staff_name,
                staff.phone,
                COUNT(wastages.id) as entries,
                COALESCE(SUM(wastages.quantity), 0) as wastage_qty,
                COALESCE(SUM(wastages.cost_loss), 0) as wastage_cost
            ')
            ->orderByDesc('wastage_cost')
            ->get();
    }

    private function getStaffOOSReports(string $startDate, string $endDate)
    {
        return OutOfStock::query()
            ->leftJoin('staff', 'staff.id', '=', 'out_of_stocks.staff_id')
            ->whereBetween('out_of_stocks.date', [$startDate, $endDate])
            ->groupBy('staff.id', 'staff.name', 'staff.phone')
            ->selectRaw('
                staff.id,
                COALESCE(staff.name, "Unknown Staff") as staff_name,
                staff.phone,
                COUNT(out_of_stocks.id) as oos_count
            ')
            ->orderByDesc('oos_count')
            ->get();
    }

    private function getTopSalesProducts(string $startDate, string $endDate)
    {
        return DailyStock::query()
            ->join('products', 'products.id', '=', 'daily_stocks.product_id')
            ->whereBetween('daily_stocks.date', [$startDate, $endDate])
            ->groupBy('products.id', 'products.product_name')
            ->selectRaw('
                products.id,
                products.product_name,
                COALESCE(SUM(daily_stocks.sales_qty), 0) as sales_qty
            ')
            ->orderByDesc('sales_qty')
            ->take(5)
            ->get();
    }

    private function getHighWastageProducts(string $startDate, string $endDate)
    {
        return Wastage::query()
            ->join('products', 'products.id', '=', 'wastages.product_id')
            ->whereBetween('wastages.date', [$startDate, $endDate])
            ->groupBy('products.id', 'products.product_name')
            ->selectRaw('
                products.id,
                products.product_name,
                COALESCE(SUM(wastages.quantity), 0) as wastage_qty,
                COALESCE(SUM(wastages.cost_loss), 0) as wastage_cost
            ')
            ->orderByDesc('wastage_cost')
            ->take(5)
            ->get();
    }
}