<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollReport extends Model
{
    protected $fillable = [
        'period_start', 'period_end', 'total_regular_hours', 'total_overtime_hours',
        'total_gross_pay', 'status', 'generated_by', 'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'total_regular_hours' => 'decimal:2',
            'total_overtime_hours' => 'decimal:2',
            'total_gross_pay' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }
}
