<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OOSSubmission extends Model
{
    protected $table = 'oos_submissions';

    protected $fillable = [
        'date',
        'staff_id',
        'submitted_time',
        'oos_count',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'oos_count' => 'integer',
        ];
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}