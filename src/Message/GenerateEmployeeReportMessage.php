<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Employee;

class GenerateEmployeeReportMessage extends AbstractGenerateEmailMessage
{
    public function __construct(
        string           $email,
        private Employee $employee,
    ) {
        parent::__construct($email);
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}
