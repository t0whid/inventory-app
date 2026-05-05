<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOOSReminderCommand extends Command
{
    protected $signature = 'oos:reminder';

    protected $description = 'Send 5 PM OOS reminder to admin Telegram';

    public function handle(TelegramService $telegramService): int
    {
        try {
            $date = Carbon::today()->format('d M Y');

            $message = "⏰ <b>OOS Reminder</b>\n\n"
                . "Please remind staff to mark today’s out-of-stock items before 5:30 PM.\n\n"
                . "<b>Date:</b> {$date}\n"
                . "<b>Time:</b> " . now()->format('h:i A');

            $sent = $telegramService->sendMessage($message, null, 'oos_reminder');

            if (!$sent) {
                $this->error('OOS reminder failed.');
                return self::FAILURE;
            }

            $this->info('OOS reminder sent successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('OOS reminder command failed', [
                'error' => $e->getMessage(),
            ]);

            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}