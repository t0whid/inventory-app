<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\Product;
use App\Services\PetpoojaService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PetpoojaSyncController extends Controller
{
    public function index()
    {
        return view('admin.petpooja-sync.index', [
            'salesDate' => Carbon::yesterday()->toDateString(),
            'report' => session('petpooja_sync_report'),
        ]);
    }

    public function sync(Request $request, PetpoojaService $petpoojaService)
    {
        $validated = $request->validate([
            'sales_date' => ['required', 'date'],
        ]);

        $salesDate = $validated['sales_date'];

        try {
            $result = $petpoojaService->fetchOrdersForSalesDate($salesDate);
            $items = $petpoojaService->extractItems($result['raw']);

            if (count($items) === 0) {
                return back()->with('error', 'No Petpooja items found for this date.');
            }

            $aggregatedItems = [];

            foreach ($items as $item) {
                $itemName = trim($item['item_name']);

                if ($itemName === '') {
                    continue;
                }

                $key = $petpoojaService->normalizeName($itemName);

                if (!isset($aggregatedItems[$key])) {
                    $aggregatedItems[$key] = [
                        'item_name' => $itemName,
                        'quantity' => 0,
                        'total' => 0,
                    ];
                }

                $aggregatedItems[$key]['quantity'] += (float) $item['quantity'];
                $aggregatedItems[$key]['total'] += (float) $item['total'];
            }

            $products = Product::all()->keyBy(function ($product) use ($petpoojaService) {
                return $petpoojaService->normalizeName($product->product_name);
            });

            $matched = [];
            $unmatched = [];

            foreach ($aggregatedItems as $key => $item) {
                $product = $products->get($key);

                if (!$product) {
                    $unmatched[] = $item;
                    continue;
                }

                $quantity = (int) round($item['quantity']);

                $dailyStock = DailyStock::firstOrNew([
                    'date' => $salesDate,
                    'product_id' => $product->id,
                ]);

                if (!$dailyStock->exists) {
                    $previousStock = DailyStock::where('product_id', $product->id)
                        ->whereDate('date', '<', $salesDate)
                        ->orderByDesc('date')
                        ->first();

                    $dailyStock->opening_stock = $previousStock?->closing_stock ?? 0;
                    $dailyStock->production_qty = 0;
                    $dailyStock->wastage_qty = 0;
                }

                /*
                 |--------------------------------------------------------------------------
                 | Important
                 |--------------------------------------------------------------------------
                 | We SET sales_qty from Petpooja total.
                 | We do not ADD, so duplicate sync will not double sales.
                 */
                $dailyStock->sales_qty = $quantity;

                $dailyStock->closing_stock =
                    (int) $dailyStock->opening_stock
                    + (int) $dailyStock->production_qty
                    - (int) $dailyStock->sales_qty
                    - (int) $dailyStock->wastage_qty;

                $dailyStock->save();

                $matched[] = [
                    'product_name' => $product->product_name,
                    'sales_qty' => $quantity,
                    'sales_amount' => $item['total'],
                    'closing_stock' => $dailyStock->closing_stock,
                ];
            }

            $report = [
                'sales_date' => $salesDate,
                'petpooja_order_date' => $result['petpooja_order_date'],
                'total_api_items' => count($items),
                'total_grouped_items' => count($aggregatedItems),
                'matched_count' => count($matched),
                'unmatched_count' => count($unmatched),
                'matched' => $matched,
                'unmatched' => $unmatched,
            ];

            return redirect()
                ->route('admin.petpooja-sync.index')
                ->with('success', 'Petpooja sales synced successfully.')
                ->with('petpooja_sync_report', $report);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}