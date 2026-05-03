<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'category',
        'cost_price',
        'selling_price',
        'shelf_life_days',
        'reorder_level',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'shelf_life_days' => 'integer',
            'reorder_level' => 'integer',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function dailyStocks()
    {
        return $this->hasMany(DailyStock::class);
    }
}
