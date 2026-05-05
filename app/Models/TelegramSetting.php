<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSetting extends Model
{
    protected $fillable = [
        'bot_token',
        'bot_username',
        'admin_chat_id',
        'is_active',
        'last_verified_at',
        'last_chat_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'bot_token' => 'encrypted',
            'is_active' => 'boolean',
            'last_verified_at' => 'datetime',
            'last_chat_synced_at' => 'datetime',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate(['id' => 1]);
    }

    public function hasBotToken(): bool
    {
        return !empty($this->bot_token);
    }

    public function hasAdminChatId(): bool
    {
        return !empty($this->admin_chat_id);
    }

    public function isReady(): bool
    {
        return $this->is_active && $this->hasBotToken() && $this->hasAdminChatId();
    }

    public function botStartUrl(): ?string
    {
        if (!$this->bot_username) {
            return null;
        }

        return 'https://t.me/' . ltrim($this->bot_username, '@');
    }
}