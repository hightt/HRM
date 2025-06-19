<?php
declare(strict_types=1);

namespace App\Model\LeaveRequest;

use Symfony\Contracts\Translation\TranslatorInterface;

enum LeaveRequestStatus: string
{
    case PENDING  = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELED = 'cancelled';

    public function label(TranslatorInterface $translator): string
    {
        return $translator->trans('leave_request.status.' . $this->value);
    }

    public function getTranslationKey(): string
    {
        return 'leave_request.status.' . $this->value;
    }


    public static function choices(TranslatorInterface $translator): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->label($translator)] = $case;
        }

        return $choices;
    }
}
