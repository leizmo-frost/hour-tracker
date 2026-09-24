<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\OvertimeRule;
use App\Models\PayrollItem;
use App\Models\PayrollReport;
use Illuminate\Support\Facades\DB;
use Throwable;

class PayrollService
{
    public function generate(string $start, string $end, int $userId): PayrollReport
    {
        return DB::transaction(function () use ($start, $end, $userId) {
            $report = PayrollReport::create([
                'period_start' => $start,
                'period_end' => $end,
                'status' => 'draft',
                'generated_by' => $userId,
            ]);

            $rule = OvertimeRule::query()->where('is_active', true)->orderBy('id')->first();
            $multiplier = (float) ($rule?->multiplier ?? 1.5);

            Employee::query()->where('is_active', true)->with([
                'timesheets' => fn ($q) => $q->where('status', 'approved')
                    ->whereDate('period_start', '>=', $start)
                    ->whereDate('period_end', '<=', $end),
            ])->chunkById(100, function ($employees) use ($report, $multiplier) {
                foreach ($employees as $employee) {
                    $regularHours = (float) $employee->timesheets->sum('regular_hours');
                    $overtimeHours = (float) $employee->timesheets->sum('overtime_hours');
                    $hourlyRate = (float) $employee->hourly_rate;
                    $regularPay = round($regularHours * $hourlyRate, 2);
                    $overtimePay = round($overtimeHours * $hourlyRate * $multiplier, 2);

                    PayrollItem::create([
                        'payroll_report_id' => $report->id,
                        'employee_id' => $employee->id,
                        'hourly_rate' => $hourlyRate,
                        'regular_hours' => $regularHours,
                        'overtime_hours' => $overtimeHours,
                        'overtime_multiplier' => $multiplier,
                        'regular_pay' => $regularPay,
                        'overtime_pay' => $overtimePay,
                        'gross_pay' => $regularPay + $overtimePay,
                    ]);
                }
            });

            $report->update([
                'total_regular_hours' => $report->items()->sum('regular_hours'),
                'total_overtime_hours' => $report->items()->sum('overtime_hours'),
                'total_gross_pay' => $report->items()->sum('gross_pay'),
            ]);

            return $report->fresh('items.employee');
        });
    }

    public function process(PayrollReport $report): PayrollReport
    {
        if ($report->status === 'processed') {
            return $report;
        }

        $report->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        return $report->fresh();
    }
}
