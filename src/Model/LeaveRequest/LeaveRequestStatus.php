<?php
declare(strict_types = 1);

namespace App\Model\LeaveRequest;

enum LeaveRequestStatus: string
{
    case PENDING  = 'pending';         // Oczekuje na decyzję
    case APPROVED = 'approved';       // Zatwierdzony
    case REJECTED = 'rejected';       // Odrzucony

    public function label(): string
    {
        return match($this) {
            self::PENDING  => 'Oczekuje',
            self::APPROVED => 'Zatwierdzony',
            self::REJECTED => 'Odrzucony',
        };
    }
}
