<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramLog extends Model
{
    protected $fillable = [
        'type',
        'chat_id',
        'message',
        'status',
        'response',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'response' => 'array',
            'sent_at' => 'datetime',
        ];
    }
}