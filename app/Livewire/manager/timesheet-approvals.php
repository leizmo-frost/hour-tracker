<?php

namespace App\Livewire\Manager;

use App\Models\Timesheet;
use App\Services\TimesheetService;
use Livewire\Component;

class TimesheetApprovals extends Component
{
    public function approve($timesheetid, TimesheetService $service)
    {
        $timesheet = Timesheet::findOrFail($timesheetid);
        $this->authorize('approve', $timesheet);
        $service->approveTimesheet($timesheet, auth()->user());
        session()->flash('message', 'Timesheet approved and locked.');
    }

    public function render()
    {
        $manager = auth()->user()->employee;
        $pendingTimesheets = Timesheet::whereHas('employee', fn($q) => $q->where('reports_to', $manager->id))
            ->where('status', 'submitted')
            ->with('employee.user')
            ->get();

        return view('livewire.manager.timesheet-approvals', compact('pendingTimesheets'));
    }
}
