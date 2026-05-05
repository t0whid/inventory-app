<?php

namespace App\Console\Commands;

use App\Models\DailyStock;
use App\Models\OOSSubmission;
use App\Models\OutOfStock;
use App\Models\Staff;
use App\Models\Wastage;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailySummaryCommand extends Command
{
    protected $signature = 'daily:summary {--date=}';

    protected $description = 'Send daily inventory summary to admin Telegram';

    public function handle(TelegramService $telegramService): int
    {
        try {
            $date = $this->option('date') ?: Carbon::today('Asia/Kolkata')->toDateString();
            $formattedDate = Carbon::parse($date)->format('d M Y');

            $stockQuery = DailyStock::query()
                ->whereDate('date', $date);

            $stockEntries = (clone $stockQuery)->count();

            $totalOpening = (clone $stockQuery)->sum('opening_stock');
            $totalProduction = (clone $stockQuery)->sum('production_qty');
            $totalSales = (clone $stockQuery)->sum('sales_qty');
            $totalWastageQty = (clone $stockQuery)->sum('wastage_qty');
            $totalClosing = (clone $stockQuery)->sum('closing_stock');

            $totalWastageCost = Wastage::query()
                ->whereDate('date', $date)
                ->sum('cost_loss');

            $oosItems = OutOfStock::query()
                ->whereDate('date', $date)
                ->count();

            $activeStaffCount = Staff::query()
                ->where('is_active', true)
                ->count();

            $submittedStaffCount = OOSSubmission::query()
                ->whereDate('date', $date)
                ->count();

            $missingStaffCount = max(0, $activeStaffCount - $submittedStaffCount);

            $topSalesProducts = DailyStock::query()
                ->with('product')
                ->whereDate('date', $date)
                ->where('sales_qty', '>', 0)
                ->orderByDesc('sales_qty')
                ->take(5)
                ->get();

            $topSalesText = $this->formatTopSalesProducts($topSalesProducts);

            $message = "📊 <b>Daily Inventory Summary</b>\n\n"
                . "<b>Date:</b> {$formattedDate}\n"
                . "<b>Generated At:</b> " . now('Asia/Kolkata')->format('h:i A') . "\n\n"
                . "📦 <b>Stock Summary</b>\n"
                . "Stock Entries: {$stockEntries}\n"
                . "Opening Qty: {$totalOpening}\n"
                . "Production Qty: {$totalProduction}\n"
                . "Sales Qty: {$totalSales}\n"
                . "Wastage Qty: {$totalWastageQty}\n"
                . "Closing Qty: {$totalClosing}\n\n"
                . "⚠️ <b>Wastage</b>\n"
                . "Wastage Cost: ₹" . number_format((float) $totalWastageCost, 2) . "\n\n"
                . "🚨 <b>OOS Summary</b>\n"
                . "OOS Items: {$oosItems}\n"
                . "Submitted Staff: {$submittedStaffCount}/{$activeStaffCount}\n"
                . "Missing Staff: {$missingStaffCount}\n\n"
                . "🏆 <b>Top Sales Products</b>\n"
                . $topSalesText;

            $sent = $telegramService->sendMessage($message, null, 'daily_summary');

            if (!$sent) {
                $this->error('Daily summary failed.');
                return self::FAILURE;
            }

            $this->info('Daily summary sent successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Daily summary command failed', [
                'error' => $e->getMessage(),
            ]);

            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }

    private function formatTopSalesProducts($topSalesProducts): string
    {
        if ($topSalesProducts->isEmpty()) {
            return "No sales data found.";
        }

        return $topSalesProducts
            ->values()
            ->map(function ($stock, $index) {
                $productName = $stock->product->product_name ?? 'Product Deleted';

                return ($index + 1) . '. ' . e($productName) . ' — ' . $stock->sales_qty;
            })
            ->implode("\n");
    }
}