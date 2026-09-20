<?php

namespace App\Policies;

use App\Models\TimesheetCorrectionRequest;
use App\Models\User;

class CorrectionRequestPolicy
{
    public function request(User $user, TimesheetCorrectionRequest $request): bool
    {
        return $user->employee && $user->employee->id === $request->employee_id;
    }

    public function approve(User $user, TimesheetCorrectionRequest $request): bool
    {
        if ($user->isAdmin()) return $user->company_id === $request->company_id;
        if ($user->isManager() && $user->employee) {
            return $user->employee->id === $request->employee->reports_to;
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
