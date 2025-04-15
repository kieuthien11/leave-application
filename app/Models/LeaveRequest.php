<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'type', 'start_date', 'start_period',
        'end_date', 'end_period', 'reason', 'status',
        'submitted_at', 'approved_by', 'decision_at',
    ];

    // Quan hệ với Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }
}
