<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutOfStock extends Model
{
    protected $fillable = [
        'date',
        'product_id',
        'staff_id',
        'marked_time',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
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