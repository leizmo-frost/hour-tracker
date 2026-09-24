<?php

namespace App\Livewire;

use App\Models\PTORequest;
use App\Models\Timesheet;
use App\Services\TimesheetService;
use Illuminate\View\View;
use Livewire\Component;

class Approvals extends Component
{
    public string $notes = '';
    public string $message = '';
    public string $messageType = 'success';

    public function reviewTimesheet(int $id, string $decision, TimesheetService $service): void
    {
        $timesheet = Timesheet::with('employee')->findOrFail($id);
        $service->review($timesheet, auth()->id(), $decision, $this->notes ?: null);
        $this->notes = '';
        $this->setMessage($decision === 'approve' ? 'Timesheet approved.' : 'Timesheet rejected.');
    }

    public function reviewPto(int $id, string $decision): void
    {
        $request = PTORequest::findOrFail($id);
        $request->update([
            'status' => $decision === 'approve' ? 'approved' : 'rejected',
            'approved_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $this->notes ?: null,
        ]);
        $this->notes = '';
        $this->setMessage($decision === 'approve' ? 'Leave request approved.' : 'Leave request rejected.');
    }

    private function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render(): View
    {
        $timesheets = Timesheet::with('employee.department')->where('status', 'submitted')->orderBy('submitted_at')->get();
        $ptoRequests = PTORequest::with('employee.department')->where('status', 'pending')->orderBy('starts_on')->get();

        return view('livewire.approvals', compact('timesheets', 'ptoRequests'))
            ->layout('layouts.app', ['title' => 'Approvals']);
    }
}
