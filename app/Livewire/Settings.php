<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Employee;
use App\Models\OvertimeRule;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Settings extends Component
{
    public string $departmentName = '';
    public string $departmentCode = '';
    public string $departmentDescription = '';
    public string $shiftName = 'Standard';
    public string $shiftStart = '08:00';
    public string $shiftEnd = '17:00';
    public int $breakMinutes = 60;
    public float $regularHours = 8;
    public $shiftEmployeeId = null;
    public string $overtimeName = 'Weekly overtime';
    public int $thresholdMinutes = 2400;
    public float $multiplier = 1.5;
    public string $message = '';
    public string $messageType = 'success';

    public function addDepartment(): void
    {
        $this->validate([
            'departmentName' => ['required','string','max:120','unique:departments,name'],
            'departmentCode' => ['nullable','string','max:20','unique:departments,code'],
            'departmentDescription' => ['nullable','string','max:500'],
        ]);
        Department::create(['name'=>$this->departmentName,'code'=>$this->departmentCode?:null,'description'=>$this->departmentDescription?:null,'is_active'=>true]);
        $this->reset(['departmentName','departmentCode','departmentDescription']);
        $this->setMessage('Department created.');
    }

    public function addShift(): void
    {
        $this->validate([
            'shiftName'=>['required','string','max:100'], 'shiftStart'=>['required','date_format:H:i'], 'shiftEnd'=>['required','date_format:H:i'],
            'breakMinutes'=>['required','integer','min:0','max:480'], 'regularHours'=>['required','numeric','min:0','max:24'], 'shiftEmployeeId'=>['required','exists:employees,id'],
        ]);
        Shift::create(['employee_id'=>$this->shiftEmployeeId,'name'=>$this->shiftName,'starts_at'=>$this->shiftStart,'ends_at'=>$this->shiftEnd,'break_minutes'=>$this->breakMinutes,'regular_hours'=>$this->regularHours,'days_per_week'=>5,'effective_from'=>today(),'is_active'=>true]);
        $this->setMessage('Shift assigned.');
    }

    public function saveOvertimeRule(): void
    {
        $this->validate(['overtimeName'=>['required','string','max:120'],'thresholdMinutes'=>['required','integer','min:1'],'multiplier'=>['required','numeric','min:1','max:5']]);
        DB::transaction(function () {
            OvertimeRule::query()->update(['is_active'=>false]);
            OvertimeRule::create(['name'=>$this->overtimeName,'period_type'=>'weekly','threshold_minutes'=>$this->thresholdMinutes,'multiplier'=>$this->multiplier,'is_active'=>true]);
        });
        $this->setMessage('Overtime rule saved and activated.');
    }

    private function setMessage(string $message, string $type='success'): void { $this->message=$message; $this->messageType=$type; }

    public function render(): View
    {
        return view('livewire.settings', [
            'departments'=>Department::with('manager')->orderBy('name')->get(),
            'employees'=>Employee::where('is_active',true)->orderBy('first_name')->get(),
            'shifts'=>Shift::with('employee')->latest()->limit(12)->get(),
            'overtimeRules'=>OvertimeRule::latest()->get(),
        ])->layout('layouts.app', ['title'=>'Settings']);
    }
}
