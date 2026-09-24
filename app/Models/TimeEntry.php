<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $fillable = [
        'employee_id', 'shift_id', 'work_date', 'clock_in', 'clock_out', 'break_minutes',
        'total_minutes', 'latitude', 'longitude', 'clock_in_ip', 'clock_out_ip', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'break_minutes' => 'integer',
            'total_minutes' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function getHoursAttribute(): float
    {
        return round($this->total_minutes / 60, 2);
    }
}
