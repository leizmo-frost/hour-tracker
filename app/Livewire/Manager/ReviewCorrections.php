<?php

namespace App\Livewire\Manager;

use App\Models\TimesheetCorrectionRequest;
use App\Services\CorrectionService;
use Livewire\Component;

class ReviewCorrections extends Component
{
    public function approve($requestId, CorrectionService $service)
    {
        $correctionRequest = TimesheetCorrectionRequest::findOrFail($requestId);
        $service->approveRequest($correctionRequest, auth()->user());
        session()->flash('message', 'Correction request approved.');
    }

    public function reject($requestId)
    {
        $correctionRequest = TimesheetCorrectionRequest::findOrFail($requestId);
        $correctionRequest->update(['status' => 'rejected', 'reviewed_by' => auth()->id()]);
        session()->flash('message', 'Correction request rejected.');
    }

    public function render()
    {
        $manager = auth()->user()->employee;
        $pendingRequests = TimesheetCorrectionRequest::whereHas('employee', fn($q) => $q->where('reports_to', $manager->id))
            ->where('status', 'pending')
            ->with(['timeEntry', 'employee.user'])
            ->get();
        return view('livewire.manager.review-corrections', compact('pendingRequests'));
    }
}
