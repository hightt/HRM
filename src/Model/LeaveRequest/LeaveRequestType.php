<?php
declare(strict_types=1);

namespace App\Model\LeaveRequest;

use Symfony\Contracts\Translation\TranslatorInterface;

enum LeaveRequestType: string
{
    case VACATION   = 'vacation';
    case ON_DEMAND  = 'on_demand';
    case UNPAID     = 'unpaid';
    case OCCASIONAL = 'occasional';
    case CHILD_CARE = 'child_care';
    case OTHER      = 'other';

    public function label(TranslatorInterface $translator): string
    {
        return $translator->trans('leave_request.type.' . $this->value);
    }

    public static function choices(TranslatorInterface $translator): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->label($translator)] = $case->value;
        }
    
        return $choices;
    }
    
}
