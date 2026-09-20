<?php

namespace App\Services;

use App\Models\{Timesheet, TimeEntry};
use Illuminate\Support\Facades\DB;

class TimesheetService
{
     public function approveTimesheet(Timesheet $timesheet, $approver): void
    {
        DB::transaction(function () use ($timesheet, $approver) {
            $timesheet->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            // Lock the time entries to enforce immutability
            $timesheet->timeEntries()->update(['is_locked' => true]);
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
