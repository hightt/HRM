<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Employee;
use App\Service\Email\EmployeeEmail;

class GenerateEmployeeReportMessage extends AbstractGenerateEmailMessage
{
    public function __construct(
        string                $email,
        private Employee      $employee,
        private EmployeeEmail $employeeEmailType,
        
    ) {
        parent::__construct($email);
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getEmployeeEmailType(): EmployeeEmail
    {
        return $this->employeeEmailType;
    }
}
