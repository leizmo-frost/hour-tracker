<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $fillable = ['company_id', 'employee_id', 'clock_in', 'clock_out', 'break_start', 'break_end', 'clock_in_method', 'location', 'device_info', 'ip_address', 'is_locked', 'correction_of', 'correction_reason'];
    protected $casts = ['clock_in' => 'datetime', 'clock_out' => 'datetime', 'break_start' => 'datetime', 'break_end' => 'datetime', 'location' => 'array', 'is_locked' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        // Prevent updates if locked
        static::updating(function ($timeEntry) {
            if ($timeEntry->is_locked) throw new \Exception("Cannot modify a locked TimeEntry. Submit a correction request.");
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getDurationAttribute(): float
    {
        if (!$this->clock_out) return 0;
        $totalMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        $breakMinutes = ($this->break_start && $this->break_end) ? $this->break_start->diffInMinutes($this->break_end) : 0;
        return max(0, ($totalMinutes - $breakMinutes) / 60);
    }
}
