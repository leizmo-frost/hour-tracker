<?php

namespace App\Livewire;

use App\Models\PayrollReport;
use App\Services\PayrollService;
use Illuminate\View\View;
use Livewire\Component;

class Payroll extends Component
{
    public string $periodStart = '';
    public string $periodEnd = '';
    public string $message = '';
    public string $messageType = 'success';
    public ?int $selectedReportId = null;

    public function mount(): void
    {
        $this->periodStart = now()->startOfMonth()->toDateString();
        $this->periodEnd = now()->endOfMonth()->toDateString();
    }

    public function generate(PayrollService $service): void
    {
        $this->validate([
            'periodStart' => ['required', 'date'],
            'periodEnd' => ['required', 'date', 'after_or_equal:periodStart'],
        ]);

        $report = $service->generate($this->periodStart, $this->periodEnd, auth()->id());
        $this->selectedReportId = $report->id;
        $this->setMessage('Payroll report generated from approved timesheets.');
    }

    public function process(int $id, PayrollService $service): void
    {
        $report = PayrollReport::findOrFail($id);
        $service->process($report);
        $this->selectedReportId = $id;
        $this->setMessage('Payroll report marked as processed.');
    }

    private function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render(): View
    {
        $reports = PayrollReport::with('generator')->latest()->limit(12)->get();
        $selected = $this->selectedReportId
            ? PayrollReport::with('items.employee')->find($this->selectedReportId)
            : $reports->first();

        return view('livewire.payroll', compact('reports', 'selected'))
            ->layout('layouts.app', ['title' => 'Payroll']);
    }
}
