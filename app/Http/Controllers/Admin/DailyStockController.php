<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyStockController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();

        $query = DailyStock::with(['product', 'staff'])
            ->whereDate('date', $date);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'negative') {
                $query->where('closing_stock', '<', 0);
            }

            if ($request->stock_status === 'low') {
                $query->whereHas('product', function ($q) {
                    $q->whereColumn('daily_stocks.closing_stock', '<', 'products.reorder_level');
                });
            }
        }

        $dailyStocks = $query
            ->orderBy('product_id')
            ->paginate(30)
            ->withQueryString();

        $products = Product::orderBy('product_name')->get();

        $summary = [
            'total_products' => DailyStock::whereDate('date', $date)->count(),
            'total_opening' => DailyStock::whereDate('date', $date)->sum('opening_stock'),
            'total_production' => DailyStock::whereDate('date', $date)->sum('production_qty'),
            'total_sales' => DailyStock::whereDate('date', $date)->sum('sales_qty'),
            'total_wastage' => DailyStock::whereDate('date', $date)->sum('wastage_qty'),
            'total_closing' => DailyStock::whereDate('date', $date)->sum('closing_stock'),
        ];

        return view('admin.daily-stocks.index', compact(
            'date',
            'dailyStocks',
            'products',
            'summary'
        ));
    }
}