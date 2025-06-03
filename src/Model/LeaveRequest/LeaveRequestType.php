<?php
declare(strict_types = 1);

namespace App\Model\LeaveRequest;

enum LeaveRequestType: string
{
    case VACATION   = 'vacation';           // Urlop wypoczynkowy
    case ON_DEMAND  = 'on_demand';         // Urlop na żądanie
    case UNPAID     = 'unpaid';               // Urlop bezpłatny
    case OCCASIONAL = 'occasional';       // Urlop okolicznościowy
    case CHILD_CARE = 'child_care';       // Opieka nad dzieckiem
    case OTHER      = 'other';                 // Inne

    public function label(): string
    {
        return match($this) {
            self::VACATION => 'Urlop wypoczynkowy',
            self::ON_DEMAND => 'Urlop na żądanie',
            self::UNPAID => 'Urlop bezpłatny',
            self::OCCASIONAL => 'Urlop okolicznościowy',
            self::CHILD_CARE => 'Opieka nad dzieckiem',
            self::OTHER => 'Inne',
        };
    }
}
