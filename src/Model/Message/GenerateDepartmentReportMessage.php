<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Department;

class GenerateDepartmentReportMessage extends AbstractGenerateEmailMessage
{
    public function __construct(
        string             $email,
        private Department $department,
    ) {
        parent::__construct($email);
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }
}
