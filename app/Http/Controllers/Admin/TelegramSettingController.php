<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramLog;
use App\Models\TelegramSetting;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramSettingController extends Controller
{
    public function index()
    {
        $setting = TelegramSetting::current();

        $logs = TelegramLog::query()
            ->latest()
            ->take(20)
            ->get();

        return view('admin.telegram-settings.index', compact('setting', 'logs'));
    }

    public function update(Request $request)
    {
        $setting = TelegramSetting::current();

        if ($setting->bot_token) {
            return redirect()
                ->route('admin.telegram-settings.index')
                ->with('error', 'Bot token is already saved. Please reset token first if you want to change it.');
        }

        $request->validate([
            'bot_token' => ['required', 'string', 'max:255'],
        ]);

        $setting->update([
            'bot_token' => trim($request->bot_token),
            'bot_username' => null,
            'admin_chat_id' => null,
            'is_active' => false,
            'last_verified_at' => null,
            'last_chat_synced_at' => null,
        ]);

        return redirect()
            ->route('admin.telegram-settings.index')
            ->with('success', 'Telegram bot token saved. Now verify the bot.');
    }

    public function resetToken()
    {
        $setting = TelegramSetting::current();

        $setting->update([
            'bot_token' => null,
            'bot_username' => null,
            'admin_chat_id' => null,
            'is_active' => false,
            'last_verified_at' => null,
            'last_chat_synced_at' => null,
        ]);

        return redirect()
            ->route('admin.telegram-settings.index')
            ->with('success', 'Telegram bot token reset successfully. You can add a new token now.');
    }

    public function verify(TelegramService $telegramService)
    {
        $result = $telegramService->verifyBot();

        if (!$result['success']) {
            return redirect()
                ->route('admin.telegram-settings.index')
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('admin.telegram-settings.index')
            ->with('success', $result['message']);
    }

    public function syncChat(TelegramService $telegramService)
    {
        $result = $telegramService->syncAdminChatId();

        if (!$result['success']) {
            return redirect()
                ->route('admin.telegram-settings.index')
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('admin.telegram-settings.index')
            ->with('success', 'Admin chat ID detected successfully.');
    }

    public function test(TelegramService $telegramService)
    {
        $sent = $telegramService->sendTestMessage();

        if (!$sent) {
            return redirect()
                ->route('admin.telegram-settings.index')
                ->with('error', 'Test message failed. Please check bot token and chat ID.');
        }

        return redirect()
            ->route('admin.telegram-settings.index')
            ->with('success', 'Test message sent successfully.');
    }
}