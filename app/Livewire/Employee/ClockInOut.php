<?php

namespace App\Livewire\Employee;

use App\Services\AttendanceService;
use Livewire\Component;

class ClockInOut extends Component
{
    public $currentShift;
    public $elapsedTime = '00:00:00';

    public function mount()
    {
        $this->currentShift = auth()->user()->employee->getCurrentShift();
    }

    public function toggleClock(AttendanceService $service)
    {
        $employee = auth()->user()->employee;
        $metadata = [
            'method' => 'web',
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'location' => json_decode(request()->header('X-Location'), true),
        ];

        if ($this->currentShift) {
            $service->clockOut($employee, $metadata);
            $this->currentShift = null;
            $this->elapsedTime = '00:00:00';
        } else {
            $this->currentShift = $service->clockIn($employee, $metadata);
        }
    }

    public function updateTimer()
    {
        if ($this->currentShift) {
            $this->elapsedTime = \Carbon\Carbon::parse($this->currentShift->clock_in)->diffForHumans(now(), ['parts' => 3, 'short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]);
        }
    }

    public function render()
    {
        return view('livewire.employee.clock-in-out');
    }
}
