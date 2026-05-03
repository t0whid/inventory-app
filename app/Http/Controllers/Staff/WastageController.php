<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Wastage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WastageController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();

        $products = Product::where('status', 'active')
            ->orderBy('product_name')
            ->get();

        $wastages = Wastage::with('product')
            ->whereDate('date', $date)
            ->latest()
            ->get();

        return view('staff.wastage.index', compact('date', 'products', 'wastages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'in:expired,damaged,unsold'],
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

        $product = Product::findOrFail($validated['product_id']);

        $dailyStock = DailyStock::whereDate('date', $validated['date'])
            ->where('product_id', $product->id)
            ->first();

        if (!$dailyStock) {
            return back()
                ->withInput()
                ->with('error', 'Please submit daily stock entry for this product first.');
        }

        $quantity = (int) $validated['quantity'];

        $newTotalWastage = $dailyStock->wastage_qty + $quantity;

        $newClosingStock = $dailyStock->opening_stock
            + $dailyStock->production_qty
            - $dailyStock->sales_qty
            - $newTotalWastage;

        if ($newClosingStock < 0) {
            return back()
                ->withInput()
                ->with('error', 'Wastage quantity is higher than available closing stock.');
        }

        $costLoss = $quantity * $product->cost_price;

        Wastage::create([
            'date' => $validated['date'],
            'product_id' => $product->id,
            'staff_id' => $staffId,
            'quantity' => $quantity,
            'reason' => $validated['reason'],
            'cost_loss' => $costLoss,
        ]);

        $dailyStock->update([
            'wastage_qty' => $newTotalWastage,
            'closing_stock' => $newClosingStock,
        ]);

        return redirect()
            ->route('staff.wastage.index', ['date' => $validated['date']])
            ->with('success', 'Wastage entry saved successfully.');
    }

    public function destroy(Wastage $wastage)
    {
        $dailyStock = DailyStock::whereDate('date', $wastage->date)
            ->where('product_id', $wastage->product_id)
            ->first();

        if ($dailyStock) {
            $newTotalWastage = max(0, $dailyStock->wastage_qty - $wastage->quantity);

            $newClosingStock = $dailyStock->opening_stock
                + $dailyStock->production_qty
                - $dailyStock->sales_qty
                - $newTotalWastage;

            $dailyStock->update([
                'wastage_qty' => $newTotalWastage,
                'closing_stock' => $newClosingStock,
            ]);
        }

        $wastage->delete();

        return back()->with('success', 'Wastage entry deleted successfully.');
    }
}