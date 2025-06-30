<?php
declare(strict_types=1);

namespace App\Model\Message;

use App\Entity\Employee;
use App\Model\Email\EmailType;

class GenerateEmployeeReportMessage extends AbstractGenerateEmailMessage
{
    public function __construct(
        string            $email,
        private Employee  $employee,
        private EmailType $emailType,
        
    ) {
        parent::__construct($email);
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getEmailType(): EmailType
    {
        return $this->emailType;
    }
}
