<?php

namespace App\Livewire;

use App\Models\PTORequest;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class LeaveRequests extends Component
{
    public string $type = 'vacation';
    public string $startsOn = '';
    public string $endsOn = '';
    public string $reason = '';
    public string $message = '';
    public string $messageType = 'success';

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:vacation,sick,personal,other'],
            'startsOn' => ['required', 'date'],
            'endsOn' => ['required', 'date', 'after_or_equal:startsOn'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();
        $employee = auth()->user()->employee;

        if (!$employee) {
            $this->setMessage('No employee profile is linked to this account.', 'error');
            return;
        }

        $days = max(1, Carbon::parse($data['startsOn'])->diffInDays(Carbon::parse($data['endsOn'])) + 1);

        PTORequest::create([
            'employee_id' => $employee->id,
            'type' => $data['type'],
            'starts_on' => $data['startsOn'],
            'ends_on' => $data['endsOn'],
            'minutes' => $days * 8 * 60,
            'reason' => $data['reason'] ?: null,
            'status' => 'pending',
        ]);

        $this->reset(['startsOn', 'endsOn', 'reason']);
        $this->setMessage('Leave request submitted.');
    }

    public function render(): View
    {
        $employee = auth()->user()->employee;
        $requests = $employee?->ptoRequests()->latest('starts_on')->get() ?? collect();

        return view('livewire.leave-requests', compact('requests'))
            ->layout('layouts.app', ['title' => 'PTO / Leave']);
    }
}
