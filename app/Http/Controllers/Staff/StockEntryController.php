<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();

        $products = Product::where('status', 'active')
            ->orderBy('product_name')
            ->get();

        $stocks = DailyStock::where('date', $date)
            ->get()
            ->keyBy('product_id');

        return view('staff.stock-entry.index', compact('date', 'products', 'stocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'stocks' => ['required', 'array'],
            'stocks.*.product_id' => ['required', 'exists:products,id'],
            'stocks.*.opening_stock' => ['required', 'integer', 'min:0'],
            'stocks.*.production_qty' => ['required', 'integer', 'min:0'],
            'stocks.*.sales_qty' => ['required', 'integer', 'min:0'],
        ]);

        $staffId = session('staff_id');

        foreach ($validated['stocks'] as $row) {
            $opening = (int) $row['opening_stock'];
            $production = (int) $row['production_qty'];
            $sales = (int) $row['sales_qty'];

            $existingStock = DailyStock::where('date', $validated['date'])
                ->where('product_id', $row['product_id'])
                ->first();

            $wastage = $existingStock?->wastage_qty ?? 0;

            $closing = $opening + $production - $sales - $wastage;

            if ($closing < 0) {
                return back()
                    ->withInput()
                    ->with('error', 'Closing stock cannot be negative. Please check your sales quantity.');
            }

            DailyStock::updateOrCreate(
                [
                    'date' => $validated['date'],
                    'product_id' => $row['product_id'],
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
        }

        return redirect()
            ->route('staff.stock-entry.index', ['date' => $validated['date']])
            ->with('success', 'Daily stock entry saved successfully.');
    }
}