<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    protected $fillable = [
        'employee_id', 'period_start', 'period_end', 'regular_hours', 'overtime_hours',
        'pto_hours', 'total_hours', 'status', 'reviewed_by', 'submitted_at', 'reviewed_at', 'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'regular_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'pto_hours' => 'decimal:2',
            'total_hours' => 'decimal:2',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
