<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_report_id', 'employee_id', 'hourly_rate', 'regular_hours', 'overtime_hours',
        'overtime_multiplier', 'regular_pay', 'overtime_pay', 'gross_pay',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'regular_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'overtime_multiplier' => 'decimal:2',
            'regular_pay' => 'decimal:2',
            'overtime_pay' => 'decimal:2',
            'gross_pay' => 'decimal:2',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(PayrollReport::class, 'payroll_report_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
