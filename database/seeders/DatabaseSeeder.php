<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Company, Employee};
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Create the Company (Tenant)
        $company = Company::create([
            'name' => 'Acme Corp',
            'settings' => [
                'missed_punch_threshold' => 3, // Our agreed threshold
                'overtime_rules' => ['daily' => 8, 'weekly' => 40]
            ],
            'status' => 'active'
        ]);

        // 2. Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $employeeRole = Role::create(['name' => 'employee']);

        // 3. Create Admin
        $adminUser = User::create([
            'company_id' => $company->id,
            'name' => 'System Admin',
            'email' => 'admin@acme.com',
            'password' => Hash::make('password'),
            'role_type' => 'super-admin',
        ]);
        $adminUser->assignRole('admin');

        // 4. Create Manager
        $managerUser = User::create([
            'company_id' => $company->id,
            'name' => 'John Manager',
            'email' => 'manager@acme.com',
            'password' => Hash::make('password'),
            'role_type' => 'manager',
        ]);
        $managerUser->assignRole('manager');

        $managerEmployee = Employee::create([
            'company_id' => $company->id,
            'user_id' => $managerUser->id,
            'employee_code' => 'MGR-001',
            'hire_date' => now()->subYear(),
            'hourly_rate' => 45.00,
            'leadership_level' => 2,
        ]);

        // 5. Create Employee (Reports to Manager)
        $employeeUser = User::create([
            'company_id' => $company->id,
            'name' => 'Jane Employee',
            'email' => 'employee@acme.com',
            'password' => Hash::make('password'),
            'role_type' => 'employee',
        ]);
        $employeeUser->assignRole('employee');

        Employee::create([
            'company_id' => $company->id,
            'user_id' => $employeeUser->id,
            'employee_code' => 'EMP-001',
            'reports_to' => $managerEmployee->id, // Links to Manager
            'hire_date' => now()->subMonths(6),
            'hourly_rate' => 25.00,
            'leadership_level' => 0,
        ]);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
