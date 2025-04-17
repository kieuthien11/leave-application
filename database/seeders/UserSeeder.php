<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee; // Thêm dòng này để sử dụng model Employee
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo một số bản ghi nhân viên
        $employeeA = Employee::create([
            'name' => 'Nhân viên A',
            'email' => 'employee_a@example.com',
            'phone' => '0123456789',
            'department' => 'Sales',
            'position' => 'Sales Representative',
            'hire_date' => now()->toDateString(),
        ]);

        $employeeB = Employee::create([
            'name' => 'Nhân viên B',
            'email' => 'employee_b@example.com',
            'phone' => '0123456790',
            'department' => 'Marketing',
            'position' => 'Marketing Specialist',
            'hire_date' => now()->toDateString(),
        ]);

        // Tạo người dùng và gán employee_id
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'employee_id' => null,  // Admin không cần employee_id
        ]);

        User::create([
            'name' => 'Nhân viên A',
            'email' => 'staff@example.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'employee_id' => $employeeA->id, // Gán employee_id cho người dùng
        ]);

        User::create([
            'name' => 'Quản lý B',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'employee_id' => $employeeB->id, // Gán employee_id cho người dùng
        ]);
    }
}
