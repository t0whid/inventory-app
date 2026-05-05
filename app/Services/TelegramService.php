<?php

namespace App\Services;

use App\Models\TelegramLog;
use App\Models\TelegramSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private ?TelegramSetting $setting = null;

    public function setting(): TelegramSetting
    {
        if (!$this->setting) {
            $this->setting = TelegramSetting::current();
        }

        return $this->setting;
    }

    private function apiUrl(string $method): string
    {
        $token = $this->setting()->bot_token;

        return "https://api.telegram.org/bot{$token}/{$method}";
    }

    public function verifyBot(): array
    {
        $setting = $this->setting();

        if (!$setting->bot_token) {
            return [
                'success' => false,
                'message' => 'Bot token is missing.',
                'data' => null,
            ];
        }

        try {
            $response = Http::timeout(15)->get($this->apiUrl('getMe'));
            $json = $response->json();

            if (!$response->successful() || !($json['ok'] ?? false)) {
                $setting->update([
                    'is_active' => false,
                    'bot_username' => null,
                    'last_verified_at' => null,
                ]);

                return [
                    'success' => false,
                    'message' => $json['description'] ?? 'Invalid Telegram bot token.',
                    'data' => $json,
                ];
            }

            $bot = $json['result'] ?? [];

            $setting->update([
                'bot_username' => $bot['username'] ?? null,
                'is_active' => true,
                'last_verified_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Telegram bot verified successfully.',
                'data' => $json,
            ];
        } catch (\Throwable $e) {
            Log::error('Telegram verify failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Telegram verification failed: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function syncAdminChatId(): array
    {
        $setting = $this->setting();

        if (!$setting->bot_token) {
            return [
                'success' => false,
                'message' => 'Bot token is missing.',
                'chat_id' => null,
                'data' => null,
            ];
        }

        try {
            $response = Http::timeout(15)->get($this->apiUrl('getUpdates'), [
                'allowed_updates' => json_encode(['message']),
            ]);

            $json = $response->json();

            if (!$response->successful() || !($json['ok'] ?? false)) {
                return [
                    'success' => false,
                    'message' => $json['description'] ?? 'Unable to fetch Telegram updates.',
                    'chat_id' => null,
                    'data' => $json,
                ];
            }

            $updates = collect($json['result'] ?? []);

            $startMessage = $updates
                ->reverse()
                ->first(function ($update) {
                    $message = $update['message'] ?? null;

                    if (!$message) {
                        return false;
                    }

                    $chat = $message['chat'] ?? [];
                    $text = trim($message['text'] ?? '');

                    return ($chat['type'] ?? null) === 'private'
                        && str_starts_with($text, '/start');
                });

            if (!$startMessage) {
                return [
                    'success' => false,
                    'message' => 'No /start message found. Please click Start in Telegram first, then try again.',
                    'chat_id' => null,
                    'data' => $json,
                ];
            }

            $chatId = (string) data_get($startMessage, 'message.chat.id');

            if (!$chatId) {
                return [
                    'success' => false,
                    'message' => 'Unable to detect admin chat ID.',
                    'chat_id' => null,
                    'data' => $json,
                ];
            }

            $setting->update([
                'admin_chat_id' => $chatId,
                'last_chat_synced_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Admin chat ID detected successfully.',
                'chat_id' => $chatId,
                'data' => $json,
            ];
        } catch (\Throwable $e) {
            Log::error('Telegram sync chat id failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Telegram chat sync failed: ' . $e->getMessage(),
                'chat_id' => null,
                'data' => null,
            ];
        }
    }

    public function sendMessage(string $message, ?string $chatId = null, string $type = 'message'): bool
    {
        $setting = $this->setting();

        $chatId = $chatId ?: $setting->admin_chat_id;

        $log = TelegramLog::create([
            'type' => $type,
            'chat_id' => $chatId,
            'message' => $message,
            'status' => 'pending',
        ]);

        if (!$setting->bot_token || !$chatId) {
            $log->update([
                'status' => 'failed',
                'response' => [
                    'message' => 'Bot token or chat ID missing.',
                ],
            ]);

            return false;
        }

        try {
            $response = Http::timeout(15)->post($this->apiUrl('sendMessage'), [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            $json = $response->json();

            if ($response->successful() && ($json['ok'] ?? false)) {
                $log->update([
                    'status' => 'success',
                    'response' => $json,
                    'sent_at' => now(),
                ]);

                return true;
            }

            $log->update([
                'status' => 'failed',
                'response' => $json,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('Telegram send failed', [
                'error' => $e->getMessage(),
            ]);

            $log->update([
                'status' => 'failed',
                'response' => [
                    'message' => $e->getMessage(),
                ],
            ]);

            return false;
        }
    }

    public function sendTestMessage(): bool
    {
        $message = "✅ <b>Telegram Connected Successfully</b>\n\n"
            . "Inventory alert system is ready.\n"
            . "Time: " . now()->format('d M Y, h:i A');

        return $this->sendMessage($message, null, 'test');
    }
}