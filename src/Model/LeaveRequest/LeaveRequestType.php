<?php
declare(strict_types = 1);

namespace App\Model\LeaveRequest;

enum LeaveRequestType: string
{
    case VACATION   = 'Urlop wypoczynkowy';           // Urlop wypoczynkowy
    case ON_DEMAND  = 'Urlop na żądanie';         // Urlop na żądanie
    case UNPAID     = 'Urlop bezpłatny';               // Urlop bezpłatny
    case OCCASIONAL = 'Urlop okolicznościowy';       // Urlop okolicznościowy
    case CHILD_CARE = 'Opieka nad dzieckiem';       // Opieka nad dzieckiem
    case OTHER      = 'Inne';                 // Inne

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

    public static function choices(): array
    {
        return array_combine(
            array_map(fn(self $case) => $case->label(), self::cases()),
            self::cases()
        );
    }

    
}
