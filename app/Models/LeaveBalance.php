<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'type', 'year', 'remaining_days',
    ];

    // Quan hệ với Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
