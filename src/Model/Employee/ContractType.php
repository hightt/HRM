<?php

namespace App\Model\Employee;

enum ContractType: string
{
    case EMPLOYMENT = 'employment';
    case B2B = 'b2b';
    case COMMISSION = 'commission';
    case WORK = 'work';

    public function label(): string
    {
        return match ($this) {
            self::EMPLOYMENT => 'employee.form.contract_type_employment',
            self::B2B => 'employee.form.contract_type_b2b',
            self::COMMISSION => 'employee.form.contract_type_commission',
            self::WORK => 'employee.form.contract_type_work',
        };
    }
}
