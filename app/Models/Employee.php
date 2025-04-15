<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'department', 'position', 'hire_date',
    ];

    // Quan hệ với Users
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    // Quan hệ với LeaveRequests
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id');
    }

    // Quan hệ với LeaveBalances
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class, 'employee_id');
    }
}
