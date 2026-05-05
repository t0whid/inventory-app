<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\OutOfStock;
use App\Models\Product;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OOSController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();
        $staffId = session('staff_id');

        $products = Product::where('status', 'active')
            ->orderBy('product_name')
            ->get();

        $selectedProductIds = OutOfStock::whereDate('date', $date)
            ->where('staff_id', $staffId)
            ->pluck('product_id')
            ->toArray();

        $oosList = OutOfStock::with(['product', 'staff'])
            ->whereDate('date', $date)
            ->latest()
            ->get();

        return view('staff.oos.index', compact(
            'date',
            'products',
            'selectedProductIds',
            'oosList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['exists:products,id'],
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

        $productIds = $validated['product_ids'] ?? [];
        $date = $validated['date'];

        DB::transaction(function () use ($date, $staffId, $productIds) {
            OutOfStock::whereDate('date', $date)
                ->where('staff_id', $staffId)
                ->delete();

            foreach ($productIds as $productId) {
                OutOfStock::create([
                    'date' => $date,
                    'product_id' => $productId,
                    'staff_id' => $staffId,
                    'marked_time' => now()->format('H:i:s'),
                ]);
            }
        });

        return redirect()
            ->route('staff.oos.index', ['date' => $date])
            ->with('success', 'OOS items saved successfully.');
    }
}