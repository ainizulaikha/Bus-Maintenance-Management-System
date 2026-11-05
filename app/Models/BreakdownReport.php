<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakdownReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'bus_id',
        'user_id',
        'report_date',
        'report_time',
        'place',
        'description',
        'pics',
        'status',
        'assigned_mechanic_id',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'repair_notes',
    ];

    protected $casts = [
        'pics' => 'array',
        'report_date' => 'date',
    ];
}
