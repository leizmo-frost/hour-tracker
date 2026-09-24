<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\OvertimeRule;
use App\Models\Timesheet;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class TimesheetService
{
    public function periodFor(CarbonInterface $date): array
    {
        return [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()];
    }

    public function buildFor(Employee $employee, CarbonInterface $start, CarbonInterface $end): Timesheet
    {
        $rule = OvertimeRule::query()->where('is_active', true)->orderBy('id')->first();
        $threshold = $rule?->threshold_minutes ?? 2400;

        $minutes = (int) $employee->timeEntries()
            ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'closed')
            ->sum('total_minutes');

        $ptoMinutes = (int) $employee->ptoRequests()
            ->where('status', 'approved')
            ->whereDate('ends_on', '>=', $start->toDateString())
            ->whereDate('starts_on', '<=', $end->toDateString())
            ->sum('minutes');

        $regularMinutes = min($minutes, $threshold);
        $overtimeMinutes = max(0, $minutes - $threshold);
        $totalMinutes = $regularMinutes + $overtimeMinutes + $ptoMinutes;

        $existing = Timesheet::query()
            ->where('employee_id', $employee->id)
            ->where('period_start', $start->toDateString())
            ->where('period_end', $end->toDateString())
            ->first();

        $status = in_array($existing?->status, ['submitted', 'approved'], true)
            ? $existing->status
            : 'draft';

        return Timesheet::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'period_start' => $start->toDateString(),
                'period_end' => $end->toDateString(),
            ],
            [
                'regular_hours' => round($regularMinutes / 60, 2),
                'overtime_hours' => round($overtimeMinutes / 60, 2),
                'pto_hours' => round($ptoMinutes / 60, 2),
                'total_hours' => round($totalMinutes / 60, 2),
                'status' => $status,
            ]
        );
    }

    public function submit(Timesheet $timesheet): Timesheet
    {
        if (!in_array($timesheet->status, ['draft', 'rejected'], true)) {
            return $timesheet;
        }

        $timesheet->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_notes' => null,
        ]);

        return $timesheet->fresh();
    }

    public function review(Timesheet $timesheet, int $reviewerId, string $decision, ?string $notes = null): Timesheet
    {
        DB::transaction(function () use ($timesheet, $reviewerId, $decision, $notes) {
            $timesheet->update([
                'status' => $decision === 'approve' ? 'approved' : 'rejected',
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
                'review_notes' => $notes,
            ]);
        });

        return $timesheet->fresh();
    }
}
