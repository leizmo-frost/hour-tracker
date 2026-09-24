<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PTORequest extends Model
{
    protected $table = 'pto_requests';

    protected $fillable = [
        'employee_id', 'type', 'starts_on', 'ends_on', 'minutes', 'reason',
        'status', 'approved_by', 'reviewed_at', 'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'minutes' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getHoursAttribute(): float
    {
        return round($this->minutes / 60, 2);
    }
}
