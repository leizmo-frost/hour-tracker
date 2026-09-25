<?php

namespace App\Livewire;

use App\Services\TimesheetService;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Timesheets extends Component
{
    public string $periodStart;
    public string $periodEnd;
    public string $message = '';
    public string $messageType = 'success';

    public function mount(): void
    {
        $service = app(TimesheetService::class);
        [$start, $end] = $service->periodFor(now());
        $this->periodStart = $start->toDateString();
        $this->periodEnd = $end->toDateString();
    }

    public function previousPeriod(TimesheetService $service): void
    {
        $start = Carbon::parse($this->periodStart)->subWeek();
        [$start, $end] = $service->periodFor($start);
        $this->periodStart = $start->toDateString();
        $this->periodEnd = $end->toDateString();
    }

    public function nextPeriod(TimesheetService $service): void
    {
        $start = Carbon::parse($this->periodStart)->addWeek();
        [$start, $end] = $service->periodFor($start);
        $this->periodStart = $start->toDateString();
        $this->periodEnd = $end->toDateString();
    }

    public function generate(TimesheetService $service): void
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            $this->setMessage('No employee profile is linked to your account.', 'error');
            return;
        }

        $service->buildFor($employee, Carbon::parse($this->periodStart), Carbon::parse($this->periodEnd));
        $this->setMessage('Timesheet totals refreshed.');
    }

    public function submit(int $timesheetId, TimesheetService $service): void
    {
        $employee = auth()->user()->employee;
        $timesheet = $employee?->timesheets()->find($timesheetId);

        if (!$timesheet) {
            $this->setMessage('Timesheet not found.', 'error');
            return;
        }

        $service->submit($timesheet);
        $this->setMessage('Timesheet submitted for approval.');
    }

    private function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render(): View
    {
        $employee = auth()->user()->employee;
        $timesheet = $employee?->timesheets()->where('period_start', $this->periodStart)->where('period_end', $this->periodEnd)->first();
        $entries = $employee?->timeEntries()->whereBetween('work_date', [$this->periodStart, $this->periodEnd])->with('shift')->orderBy('work_date')->orderBy('clock_in')->get() ?? collect();
        $history = $employee?->timesheets()->latest('period_start')->limit(10)->get() ?? collect();

        return view('livewire.timesheets', compact('timesheet', 'entries', 'history'))
            ->layout('layouts.app', ['title' => 'Timesheets']);
    }
}
