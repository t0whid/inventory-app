<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DailyStock;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Wastage;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WastageController extends Controller
{
    private const HIGH_WASTAGE_LIMIT = 500;

    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();

        $products = Product::where('status', 'active')
            ->orderBy('product_name')
            ->get();

        $wastages = Wastage::with(['product', 'staff'])
            ->whereDate('date', $date)
            ->latest()
            ->get();

        return view('staff.wastage.index', compact('date', 'products', 'wastages'));
    }

    public function store(Request $request, TelegramService $telegramService)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'in:expired,damaged,unsold'],
        ]);

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

        $product = Product::findOrFail($validated['product_id']);

        $quantity = (int) $validated['quantity'];
        $date = $validated['date'];
        $reason = $validated['reason'];

        $wastage = null;
        $costLoss = 0;

        try {
            DB::transaction(function () use (
                $date,
                $product,
                $staffId,
                $quantity,
                $reason,
                &$wastage,
                &$costLoss
            ) {
                $dailyStock = DailyStock::whereDate('date', $date)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if (!$dailyStock) {
                    throw new \RuntimeException('Please submit daily stock entry for this product first.');
                }

                $newTotalWastage = $dailyStock->wastage_qty + $quantity;

                $newClosingStock = $dailyStock->opening_stock
                    + $dailyStock->production_qty
                    - $dailyStock->sales_qty
                    - $newTotalWastage;

                if ($newClosingStock < 0) {
                    throw new \RuntimeException('Wastage quantity is higher than available closing stock.');
                }

                $costLoss = $quantity * $product->cost_price;

                $wastage = Wastage::create([
                    'date' => $date,
                    'product_id' => $product->id,
                    'staff_id' => $staffId,
                    'quantity' => $quantity,
                    'reason' => $reason,
                    'cost_loss' => $costLoss,
                ]);

                $dailyStock->update([
                    'wastage_qty' => $newTotalWastage,
                    'closing_stock' => $newClosingStock,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Wastage save failed', [
                'staff_id' => $staffId,
                'product_id' => $product->id,
                'date' => $date,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while saving wastage entry.');
        }

        $this->sendHighWastageAlert(
            telegramService: $telegramService,
            staff: $staff,
            product: $product,
            date: $date,
            quantity: $quantity,
            reason: $reason,
            costLoss: $costLoss
        );

        return redirect()
            ->route('staff.wastage.index', ['date' => $date])
            ->with('success', 'Wastage entry saved successfully.');
    }

    public function destroy(Wastage $wastage)
    {
        try {
            DB::transaction(function () use ($wastage) {
                $dailyStock = DailyStock::whereDate('date', $wastage->date)
                    ->where('product_id', $wastage->product_id)
                    ->lockForUpdate()
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
            });
        } catch (\Throwable $e) {
            Log::error('Wastage delete failed', [
                'wastage_id' => $wastage->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Something went wrong while deleting wastage entry.');
        }

        return back()->with('success', 'Wastage entry deleted successfully.');
    }

    private function sendHighWastageAlert(
        TelegramService $telegramService,
        Staff $staff,
        Product $product,
        string $date,
        int $quantity,
        string $reason,
        float|int|string $costLoss
    ): void {
        try {
            $costLoss = (float) $costLoss;

            if ($costLoss < self::HIGH_WASTAGE_LIMIT) {
                return;
            }

            $formattedDate = Carbon::parse($date)->format('d M Y');
            $formattedTime = now()->format('h:i A');
            $formattedReason = ucfirst($reason);
            $formattedCostLoss = number_format($costLoss, 2);

            $message = "⚠️ <b>High Wastage Alert</b>\n\n"
                . "<b>Product:</b> " . e($product->product_name) . "\n"
                . "<b>Staff:</b> " . e($staff->name) . "\n"
                . "<b>Date:</b> {$formattedDate}\n"
                . "<b>Time:</b> {$formattedTime}\n"
                . "<b>Quantity:</b> {$quantity}\n"
                . "<b>Reason:</b> {$formattedReason}\n"
                . "<b>Cost Loss:</b> ₹{$formattedCostLoss}\n\n"
                . "Action Needed";

            $telegramService->sendMessage($message, null, 'high_wastage_alert');
        } catch (\Throwable $e) {
            Log::error('High wastage Telegram alert failed', [
                'staff_id' => $staff->id,
                'product_id' => $product->id,
                'date' => $date,
                'quantity' => $quantity,
                'reason' => $reason,
                'cost_loss' => $costLoss,
                'error' => $e->getMessage(),
            ]);
        }
    }
}