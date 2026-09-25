<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\PTORequest;
use App\Models\TimeEntry;
use Illuminate\View\View;
use Livewire\Component;

class Reports extends Component
{
    public string $periodStart = '';
    public string $periodEnd = '';

    public function mount(): void
    {
        $this->periodStart = now()->startOfWeek()->toDateString();
        $this->periodEnd = now()->endOfWeek()->toDateString();
    }

    public function render(): View
    {
        $entriesQuery = TimeEntry::query()->whereBetween('work_date', [$this->periodStart, $this->periodEnd])->where('status', 'closed');
        $totalMinutes = (int) $entriesQuery->sum('total_minutes');
        $ptoMinutes = (int) PTORequest::query()->where('status', 'approved')->whereDate('ends_on', '>=', $this->periodStart)->whereDate('starts_on', '<=', $this->periodEnd)->sum('minutes');

        $employees = Employee::query()->where('is_active', true)->with(['department'])->orderBy('first_name')->get()->map(function (Employee $employee) {
            $minutes = (int) $employee->timeEntries()->whereBetween('work_date', [$this->periodStart, $this->periodEnd])->where('status', 'closed')->sum('total_minutes');
            $overtime = max(0, $minutes - (40 * 60));
            return [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'department' => $employee->department?->name ?? 'Unassigned',
                'hours' => round($minutes / 60, 2),
                'overtime' => round($overtime / 60, 2),
                'rate' => (float) $employee->hourly_rate,
                'gross' => round((($minutes - $overtime) / 60) * (float) $employee->hourly_rate + (($overtime / 60) * (float) $employee->hourly_rate * 1.5), 2),
            ];
        });

        return view('livewire.reports', [
            'employees' => $employees,
            'metrics' => [
                'hours' => round($totalMinutes / 60, 2),
                'overtime' => round(max(0, $totalMinutes - (40 * 60 * max(1, $employees->count()))) / 60, 2),
                'pto' => round($ptoMinutes / 60, 2),
                'gross' => round($employees->sum('gross'), 2),
            ],
        ])->layout('layouts.app', ['title' => 'Reports']);
    }
}
