<?php

namespace App\Services;

use App\Models\{TimesheetCorrectionRequest, TimeEntry};
use Illuminate\Support\Facades\DB;

class CorrectionService
{
     public function approveRequest(TimesheetCorrectionRequest $request, $approver): void
    {
        DB::transaction(function () use ($request, $approver) {
            $request->update([
                'status' => 'approved',
                'reviewed_by' => $approver->id,
            ]);

            // Create an ADJUSTING TimeEntry. Never touch the original.
            TimeEntry::create([
                'company_id' => $request->company_id,
                'employee_id' => $request->employee_id,
                'clock_in' => $request->timeEntry->clock_in,
                'clock_out' => $request->timeEntry->clock_in->copy()->addHours($request->requested_hours),
                'clock_in_method' => 'correction',
                'correction_of' => $request->time_entry_id,
                'correction_reason' => $request->reason,
            ]);
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
