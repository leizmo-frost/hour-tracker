<?php

namespace App\Livewire;

use App\Models\Shift;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Clock extends Component
{
    public string $notes = '';
    public int $breakMinutes = 0;
    public $activeEntry = null;
    public array $entries = [];
    public string $message = '';
    public string $messageType = 'success';

    public function mount(): void
    {
        $this->refreshState();
    }

    public function refreshState(): void
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            $this->activeEntry = null;
            $this->entries = [];
            return;
        }

        $entry = $employee->timeEntries()->where('status', 'open')->latest('clock_in')->first();
        $this->activeEntry = $entry ? $entry->toArray() : null;
        $this->entries = $employee->timeEntries()->with('shift')->latest('clock_in')->limit(12)->get()->toArray();
    }

    public function clockIn(): void
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->is_active) {
            $this->setMessage('Your employee profile is inactive.', 'error');
            return;
        }

        if ($employee->timeEntries()->where('status', 'open')->exists()) {
            $this->setMessage('You already have an open time entry.', 'error');
            return;
        }

        $shift = Shift::query()
            ->where('employee_id', $employee->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('effective_from')->orWhereDate('effective_from', '<=', today());
            })
            ->where(function ($q) {
                $q->whereNull('effective_to')->orWhereDate('effective_to', '>=', today());
            })
            ->latest('id')
            ->first();

        TimeEntry::create([
            'employee_id' => $employee->id,
            'shift_id' => $shift?->id,
            'work_date' => today(),
            'clock_in' => now(),
            'break_minutes' => max(0, $this->breakMinutes),
            'status' => 'open',
            'notes' => $this->notes ?: null,
            'clock_in_ip' => request()->ip(),
        ]);

        $this->notes = '';
        $this->breakMinutes = 0;
        $this->setMessage('Clock-in recorded.');
        $this->refreshState();
    }

    public function clockOut(): void
    {
        $employee = auth()->user()->employee;
        $entry = $employee?->timeEntries()->where('status', 'open')->latest('clock_in')->first();

        if (!$entry) {
            $this->setMessage('No open time entry was found.', 'error');
            return;
        }

        $entry->update([
            'clock_out' => now(),
            'total_minutes' => max(0, $entry->clock_in->diffInMinutes(now()) - $entry->break_minutes),
            'clock_out_ip' => request()->ip(),
            'status' => 'closed',
        ]);

        $this->setMessage('Clock-out recorded.');
        $this->refreshState();
    }

    private function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render(): View
    {
        return view('livewire.clock')->layout('layouts.app', ['title' => 'Clock In / Out']);
    }
}
