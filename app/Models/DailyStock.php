<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStock extends Model
{
    protected $fillable = [
        'date',
        'product_id',
        'staff_id',
        'opening_stock',
        'production_qty',
        'sales_qty',
        'wastage_qty',
        'closing_stock',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'opening_stock' => 'integer',
            'production_qty' => 'integer',
            'sales_qty' => 'integer',
            'wastage_qty' => 'integer',
            'closing_stock' => 'integer',
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