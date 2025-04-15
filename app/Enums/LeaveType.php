<?php

namespace App\Enums;

class LeaveType
{
    const ANNUAL = 'annual';
    const UNPAID = 'unpaid';
    const MATERNITY = 'maternity';

    public static function all(): array
    {
        return [
            self::ANNUAL => 'Annual Leave',
            self::UNPAID => 'Unpaid Leave',
            self::MATERNITY => 'Maternity Leave',
        ];
    }
}
