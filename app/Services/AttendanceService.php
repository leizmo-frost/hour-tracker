<?php

namespace App\Services;

use App\Models\TimeEntry;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;


class AttendanceService
{
    public function clockIn(Employee $employee, array $metadata): TimeEntry
    {
        return DB::transaction(function () use ($employee, $metadata) {
            if ($employee->isCurrentlyClockedIn()) {
                throw new \Exception("You are already clocked in.");
            }

            return TimeEntry::create([
                'company_id' => $employee->company_id,
                'employee_id' => $employee->id,
                'clock_in' => now(), // Server-side time
                'clock_in_method' => $metadata['method'] ?? 'web',
                'location' => $metadata['location'],
                'device_info' => $metadata['device_info'],
                'ip_address' => $metadata['ip_address'],
            ]);
        });
    }

    public function clockOut(Employee $employee, array $metadata): TimeEntry
    {
        return DB::transaction(function () use ($employee, $metadata) {
            $timeEntry = $employee->getCurrentShift();
            if (!$timeEntry) throw new \Exception("No active shift found.");

            $timeEntry->update([
                'clock_out' => now(),
                'clock_out_method' => $metadata['method'] ?? 'web',
            ]);
            return $timeEntry->fresh();
        });
    }

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
}
