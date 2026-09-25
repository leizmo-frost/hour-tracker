<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\OvertimeRule;
use App\Models\PTORequest;
use App\Models\Shift;
use App\Models\TimeEntry;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Grace Manager',
            'email' => 'manager@example.com',
            'password' => 'password',
            'role' => 'manager',
        ]);

        $department = Department::create([
            'name' => 'Operations',
            'code' => 'OPS',
            'manager_id' => $manager->id,
            'description' => 'Core operations and service delivery.',
            'is_active' => true,
        ]);

        $departmentTwo = Department::create([
            'name' => 'Technology',
            'code' => 'TECH',
            'description' => 'Software and technology operations.',
            'is_active' => true,
        ]);

        $aliceUser = User::create([
            'name' => 'Alice Employee',
            'email' => 'employee@example.com',
            'password' => 'password',
            'role' => 'employee',
        ]);

        $bobUser = User::create([
            'name' => 'Brian Developer',
            'email' => 'brian@example.com',
            'password' => 'password',
            'role' => 'employee',
        ]);

        $alice = Employee::create([
            'user_id' => $aliceUser->id,
            'department_id' => $department->id,
            'employee_number' => 'EMP-001',
            'first_name' => 'Alice',
            'last_name' => 'Employee',
            'phone' => '+254700000001',
            'job_title' => 'Operations Associate',
            'hire_date' => now()->subYear(),
            'hourly_rate' => 12.50,
            'annual_pto_minutes' => 120 * 60,
            'is_active' => true,
        ]);

        $brian = Employee::create([
            'user_id' => $bobUser->id,
            'department_id' => $departmentTwo->id,
            'employee_number' => 'EMP-002',
            'first_name' => 'Brian',
            'last_name' => 'Developer',
            'phone' => '+254700000002',
            'job_title' => 'Software Developer',
            'hire_date' => now()->subMonths(6),
            'hourly_rate' => 18.00,
            'annual_pto_minutes' => 120 * 60,
            'is_active' => true,
        ]);

        $aliceShift = Shift::create([
            'employee_id' => $alice->id, 'name' => 'Operations Day', 'starts_at' => '08:00', 'ends_at' => '17:00',
            'break_minutes' => 60, 'regular_hours' => 8, 'days_per_week' => 5, 'effective_from' => today(), 'is_active' => true,
        ]);
        $brianShift = Shift::create([
            'employee_id' => $brian->id, 'name' => 'Engineering Day', 'starts_at' => '09:00', 'ends_at' => '18:00',
            'break_minutes' => 60, 'regular_hours' => 8, 'days_per_week' => 5, 'effective_from' => today(), 'is_active' => true,
        ]);

        OvertimeRule::create([
            'name' => 'Weekly overtime', 'period_type' => 'weekly', 'threshold_minutes' => 2400, 'multiplier' => 1.50, 'is_active' => true,
        ]);

        $weekStart = now()->startOfWeek();
        foreach (range(0, 4) as $i) {
            $date = $weekStart->copy()->addDays($i);
            $in = $date->copy()->setTime(8, 0);
            $out = $date->copy()->setTime(17, 0);
            TimeEntry::create([
                'employee_id' => $alice->id, 'shift_id' => $aliceShift->id, 'work_date' => $date->toDateString(),
                'clock_in' => $in, 'clock_out' => $out, 'break_minutes' => 60, 'total_minutes' => 480,
                'status' => 'closed', 'clock_in_ip' => '127.0.0.1', 'clock_out_ip' => '127.0.0.1',
            ]);
        }

        foreach (range(0, 3) as $i) {
            $date = $weekStart->copy()->addDays($i);
            $in = $date->copy()->setTime(9, 0);
            $out = $date->copy()->setTime(18, 30);
            TimeEntry::create([
                'employee_id' => $brian->id, 'shift_id' => $brianShift->id, 'work_date' => $date->toDateString(),
                'clock_in' => $in, 'clock_out' => $out, 'break_minutes' => 60, 'total_minutes' => 510,
                'status' => 'closed', 'clock_in_ip' => '127.0.0.1', 'clock_out_ip' => '127.0.0.1',
            ]);
        }

        Timesheet::create([
            'employee_id' => $alice->id, 'period_start' => $weekStart->toDateString(), 'period_end' => $weekStart->copy()->endOfWeek()->toDateString(),
            'regular_hours' => 40, 'overtime_hours' => 0, 'pto_hours' => 0, 'total_hours' => 40, 'status' => 'submitted', 'submitted_at' => now(),
        ]);

        PTORequest::create([
            'employee_id' => $alice->id, 'type' => 'vacation', 'starts_on' => now()->addWeek()->startOfWeek()->toDateString(),
            'ends_on' => now()->addWeek()->startOfWeek()->addDays(1)->toDateString(), 'minutes' => 16 * 60,
            'reason' => 'Personal leave', 'status' => 'pending',
        ]);

        $admin->touch();
    }
}
