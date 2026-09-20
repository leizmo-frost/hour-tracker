<?php

namespace App\Policies;

use App\Models\Timesheet;
use App\Models\User;

class TimesheetPolicy
{
    public function approve(User $user, Timesheet $timesheet): bool
    {
        // Admin can approve anyone in their company
        if ($user->isAdmin()) return $user->company_id === $timesheet->company_id;

        // Manager can only approve their direct reports
        if ($user->isManager() && $user->employee) {
            return $user->employee->id === $timesheet->employee->reports_to;
        }
        return false;
    }

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
}
