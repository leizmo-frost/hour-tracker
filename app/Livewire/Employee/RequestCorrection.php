<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\TimesheetCorrectionRequest;

class RequestCorrection extends Component
{
    public $timeEntryId;
    public $requestedHours;
    public $reason;

    protected $rules = [
        'requestedHours' => 'required|numeric|min:0|max:24',
        'reason' => 'required|string|min:10|max:500',
    ];

    public function submit()
    {
        $this->validate();

        TimesheetCorrectionRequest::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => auth()->user()->employee->id,
            'time_entry_id' => $this->timeEntryId,
            'requested_hours' => $this->requestedHours,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Correction request submitted successfully.');
        $this->reset(['requestedHours', 'reason']);
    }

    public function render()
    {
        return view('livewire.employee.request-correction');
    }
}
