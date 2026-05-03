<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wastage extends Model
{
    protected $fillable = [
        'date',
        'product_id',
        'staff_id',
        'quantity',
        'reason',
        'cost_loss',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'quantity' => 'integer',
            'cost_loss' => 'decimal:2',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}