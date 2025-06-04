<?php
declare(strict_types = 1);

namespace App\Model\LeaveRequest;

enum LeaveRequestStatus: string
{
    case PENDING  = 'Oczekuje';         // Oczekuje na decyzję
    case APPROVED = 'Zatwierdzony';       // Zatwierdzony
    case REJECTED = 'Odrzucony';       // Odrzucony

    public function label(): string
    {
        return match($this) {
            self::PENDING  => 'Oczekuje',
            self::APPROVED => 'Zatwierdzony',
            self::REJECTED => 'Odrzucony',
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
