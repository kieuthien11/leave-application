<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Enums\LeaveType; // Import Enum LeaveType

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả các nhân viên
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Chỉ tạo leave balance cho loại nghỉ phép Annual
            LeaveBalance::create([
                'employee_id' => $employee->id,
                'type' => 'Annual Leave', // Dùng loại nghỉ phép Annual
                'year' => now()->year, // Gán năm hiện tại
                'remaining_days' => 12, // Số ngày nghỉ còn lại cho loại Annual
            ]);
        }
    }
}
