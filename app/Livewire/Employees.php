<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Employees extends Component
{
    public bool $showForm = false;
    public ?int $employeeId = null;
    public string $name = '';
    public string $email = '';
    public string $firstName = '';
    public string $lastName = '';
    public string $employeeNumber = '';
    public string $phone = '';
    public string $jobTitle = '';
    public $departmentId = null;
    public string $hourlyRate = '0';
    public string $hireDate = '';
    public bool $isActive = true;
    public string $message = '';
    public string $messageType = 'success';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->employeeId ? Employee::find($this->employeeId)?->user_id : null)],
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'employeeNumber' => ['required', 'string', 'max:40', Rule::unique('employees', 'employee_number')->ignore($this->employeeId)],
            'phone' => ['nullable', 'string', 'max:40'],
            'jobTitle' => ['nullable', 'string', 'max:120'],
            'departmentId' => ['nullable', 'exists:departments,id'],
            'hourlyRate' => ['required', 'numeric', 'min:0'],
            'hireDate' => ['nullable', 'date'],
            'isActive' => ['boolean'],
        ];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $employee = Employee::with('user')->findOrFail($id);
        $this->employeeId = $employee->id;
        $this->name = $employee->user->name;
        $this->email = $employee->user->email;
        $this->firstName = $employee->first_name;
        $this->lastName = $employee->last_name;
        $this->employeeNumber = $employee->employee_number;
        $this->phone = $employee->phone ?? '';
        $this->jobTitle = $employee->job_title ?? '';
        $this->departmentId = $employee->department_id;
        $this->hourlyRate = (string) $employee->hourly_rate;
        $this->hireDate = $employee->hire_date?->toDateString() ?? '';
        $this->isActive = $employee->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        $wasEditing = (bool) $this->employeeId;

        DB::transaction(function () use ($data) {
            if ($this->employeeId) {
                $employee = Employee::with('user')->findOrFail($this->employeeId);
                $employee->user->update(['name' => $data['name'], 'email' => $data['email']]);
                $employee->update([
                    'department_id' => $data['departmentId'], 'employee_number' => $data['employeeNumber'],
                    'first_name' => $data['firstName'], 'last_name' => $data['lastName'], 'phone' => $data['phone'] ?: null,
                    'job_title' => $data['jobTitle'] ?: null, 'hourly_rate' => $data['hourlyRate'],
                    'hire_date' => $data['hireDate'] ?: null, 'is_active' => $data['isActive'],
                ]);
            } else {
                $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => 'password', 'role' => 'employee']);
                Employee::create([
                    'user_id' => $user->id, 'department_id' => $data['departmentId'], 'employee_number' => $data['employeeNumber'],
                    'first_name' => $data['firstName'], 'last_name' => $data['lastName'], 'phone' => $data['phone'] ?: null,
                    'job_title' => $data['jobTitle'] ?: null, 'hourly_rate' => $data['hourlyRate'], 'hire_date' => $data['hireDate'] ?: today(),
                    'annual_pto_minutes' => 120 * 60, 'is_active' => $data['isActive'],
                ]);
            }
        });

        $this->showForm = false;
        $this->resetForm();
        $this->setMessage($wasEditing ? 'Employee updated.' : 'Employee created. Default password: password');
    }

    public function deactivate(int $id): void
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['is_active' => !$employee->is_active]);
        $this->setMessage($employee->is_active ? 'Employee activated.' : 'Employee deactivated.');
    }

    public function resetForm(): void
    {
        $this->reset(['employeeId', 'name', 'email', 'firstName', 'lastName', 'employeeNumber', 'phone', 'jobTitle', 'departmentId']);
        $this->hourlyRate = '0';
        $this->hireDate = '';
        $this->isActive = true;
        $this->resetValidation();
    }

    private function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render(): View
    {
        return view('livewire.employees', [
            'employees' => Employee::with(['department', 'user'])->orderBy('first_name')->get(),
            'departments' => Department::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Employees']);
    }
}
