<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\OOSSubmission;
use App\Models\OutOfStock;
use App\Models\Product;
use App\Models\Staff;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $submission = OOSSubmission::whereDate('date', $date)
            ->where('staff_id', $staffId)
            ->first();

        return view('staff.oos.index', compact(
            'date',
            'products',
            'selectedProductIds',
            'oosList',
            'submission'
        ));
    }

    public function store(Request $request, TelegramService $telegramService)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['exists:products,id'],
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

            OOSSubmission::updateOrCreate(
                [
                    'date' => $date,
                    'staff_id' => $staffId,
                ],
                [
                    'submitted_time' => now()->format('H:i:s'),
                    'oos_count' => count($productIds),
                ]
            );
        });

        $this->sendOOSTelegramAlert(
            telegramService: $telegramService,
            staff: $staff,
            date: $date,
            productIds: $productIds
        );

        return redirect()
            ->route('staff.oos.index', ['date' => $date])
            ->with('success', 'OOS items saved successfully.');
    }

    private function sendOOSTelegramAlert(
        TelegramService $telegramService,
        Staff $staff,
        string $date,
        array $productIds
    ): void {
        try {
            if (empty($productIds)) {
                return;
            }

            $products = Product::whereIn('id', $productIds)
                ->orderBy('product_name')
                ->get();

            if ($products->isEmpty()) {
                return;
            }

            $itemLines = $products
                ->values()
                ->map(function ($product, $index) {
                    return ($index + 1) . '. ' . e($product->product_name);
                })
                ->implode("\n");

            $formattedDate = Carbon::parse($date)->format('d M Y');
            $formattedTime = now()->format('h:i A');

            $message = "🚨 <b>OOS Alert</b>\n\n"
                . "<b>Staff:</b> " . e($staff->name) . "\n"
                . "<b>Date:</b> {$formattedDate}\n"
                . "<b>Time:</b> {$formattedTime}\n\n"
                . "<b>Out of Stock Items:</b>\n"
                . $itemLines . "\n\n"
                . "Action Needed";

            $telegramService->sendMessage($message, null, 'oos_alert');
        } catch (\Throwable $e) {
            Log::error('OOS Telegram alert failed', [
                'staff_id' => $staff->id,
                'date' => $date,
                'product_ids' => $productIds,
                'error' => $e->getMessage(),
            ]);
        }
    }
}