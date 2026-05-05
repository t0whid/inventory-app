<?php

namespace App\Console\Commands;

use App\Models\OOSSubmission;
use App\Models\Staff;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOOSMissingAlertCommand extends Command
{
    protected $signature = 'oos:missing-alert';

    protected $description = 'Send missing OOS submission alert to admin Telegram';

    public function handle(TelegramService $telegramService): int
    {
        try {
            $date = Carbon::today()->toDateString();
            $formattedDate = Carbon::parse($date)->format('d M Y');

            $submittedStaffIds = OOSSubmission::whereDate('date', $date)
                ->pluck('staff_id')
                ->toArray();

            $missingStaffs = Staff::where('is_active', true)
                ->whereNotIn('id', $submittedStaffIds)
                ->orderBy('name')
                ->get();

            if ($missingStaffs->isEmpty()) {
                $message = "✅ <b>OOS Submission Complete</b>\n\n"
                    . "All active staff submitted today’s OOS update.\n\n"
                    . "<b>Date:</b> {$formattedDate}\n"
                    . "<b>Checked At:</b> " . now()->format('h:i A');

                $telegramService->sendMessage($message, null, 'oos_all_submitted');

                $this->info('All staff submitted OOS.');
                return self::SUCCESS;
            }

            $staffLines = $missingStaffs
                ->values()
                ->map(function ($staff, $index) {
                    $phone = $staff->phone ? " ({$staff->phone})" : '';

                    return ($index + 1) . '. ' . e($staff->name) . $phone;
                })
                ->implode("\n");

            $message = "⚠️ <b>OOS Missing Alert</b>\n\n"
                . "These active staff did not submit today’s OOS update:\n\n"
                . $staffLines . "\n\n"
                . "<b>Date:</b> {$formattedDate}\n"
                . "<b>Checked At:</b> " . now()->format('h:i A') . "\n\n"
                . "Please follow up.";

            $sent = $telegramService->sendMessage($message, null, 'oos_missing_alert');

            if (!$sent) {
                $this->error('OOS missing alert failed.');
                return self::FAILURE;
            }

            $this->info('OOS missing alert sent successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('OOS missing alert command failed', [
                'error' => $e->getMessage(),
            ]);

            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}