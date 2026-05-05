<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\Product;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();
        $selectedProductId = $request->product_id;

        $products = Product::where('status', 'active')
            ->orderBy('product_name')
            ->get();

        $selectedProduct = null;
        $stock = null;
        $previousOpeningStock = 0;

        if ($selectedProductId) {
            $selectedProduct = Product::where('status', 'active')
                ->where('id', $selectedProductId)
                ->first();

            if ($selectedProduct) {
                $stock = DailyStock::whereDate('date', $date)
                    ->where('product_id', $selectedProduct->id)
                    ->first();

                $previousStock = DailyStock::where('product_id', $selectedProduct->id)
                    ->whereDate('date', '<', $date)
                    ->orderByDesc('date')
                    ->first();

                $previousOpeningStock = $previousStock?->closing_stock ?? 0;
            }
        }

        return view('staff.stock-entry.index', compact(
            'date',
            'products',
            'selectedProductId',
            'selectedProduct',
            'stock',
            'previousOpeningStock'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'production_qty' => ['required', 'integer', 'min:0'],
            'sales_qty' => ['required', 'integer', 'min:0'],
        ]);

        $staffId = session('staff_id');

        if (!$staffId || !Staff::where('id', $staffId)->where('is_active', true)->exists()) {
            $request->session()->forget([
                'staff_id',
                'staff_name',
                'staff_phone',
            ]);

            return redirect()
                ->route('staff.login')
                ->with('error', 'Staff session expired. Please login again.');
        }

        $productId = (int) $validated['product_id'];
        $date = $validated['date'];

        $existingStock = DailyStock::whereDate('date', $date)
            ->where('product_id', $productId)
            ->first();

        $previousStock = DailyStock::where('product_id', $productId)
            ->whereDate('date', '<', $date)
            ->orderByDesc('date')
            ->first();

        /*
         |--------------------------------------------------------------------------
         | Opening Stock Auto
         |--------------------------------------------------------------------------
         | Today's opening stock comes from previous available day's closing stock.
         */
        $opening = $previousStock?->closing_stock ?? 0;

        $production = (int) $validated['production_qty'];
        $sales = (int) $validated['sales_qty'];

        /*
         |--------------------------------------------------------------------------
         | Wastage
         |--------------------------------------------------------------------------
         | Wastage is not entered from stock entry screen.
         | It will remain from wastage entry module if already exists.
         */
        $wastage = $existingStock?->wastage_qty ?? 0;

        $closing = $opening + $production - $sales - $wastage;

        DailyStock::updateOrCreate(
            [
                'date' => $date,
                'product_id' => $productId,
            ],
            [
                'staff_id' => $staffId,
                'opening_stock' => $opening,
                'production_qty' => $production,
                'sales_qty' => $sales,
                'wastage_qty' => $wastage,
                'closing_stock' => $closing,
            ]
        );

        return redirect()
            ->route('staff.stock-entry.index', [
                'date' => $date,
                'product_id' => $productId,
            ])
            ->with('success', 'Daily stock entry saved successfully.');
    }
}