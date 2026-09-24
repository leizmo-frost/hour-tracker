<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\PTORequest;
use App\Models\TimeEntry;
use App\Models\Timesheet;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $user = auth()->user();
        $employee = $user->employee;

        $stats = [
            'employees' => $user->isManager() ? Employee::where('is_active', true)->count() : 1,
            'hours' => 0,
            'overtime' => 0,
            'pending' => 0,
        ];

        $recentEntries = collect();
        $recentTimesheets = collect();

        if ($employee) {
            $stats['hours'] = round((float) $employee->timeEntries()->where('status', 'closed')->whereBetween('work_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('total_minutes') / 60, 2);
            $stats['overtime'] = round(max(0, $stats['hours'] - 40), 2);
            $stats['pending'] = $employee->ptoRequests()->where('status', 'pending')->count() + $employee->timesheets()->where('status', 'submitted')->count();
            $recentEntries = $employee->timeEntries()->with('shift')->latest('clock_in')->limit(6)->get();
            $recentTimesheets = $employee->timesheets()->latest('period_start')->limit(4)->get();
        }

        if ($user->isManager()) {
            $stats['pending'] = Timesheet::where('status', 'submitted')->count() + PTORequest::where('status', 'pending')->count();
        }

        return view('livewire.dashboard', compact('stats', 'recentEntries', 'recentTimesheets', 'employee'))
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
