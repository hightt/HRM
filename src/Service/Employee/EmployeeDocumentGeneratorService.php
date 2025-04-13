<?php
declare(strict_types = 1);

namespace App\Service\Employee;

use App\Entity\Employee;
use DateTime;

class EmployeeDocumentGeneratorService 
{
    public function __construct() 
    {}

    public function createDocumentName(Employee $employee, string $documentPrefix)
    {
        return sprintf('%s_%s_%s_%s', 
            (new Datetime())->format('Ymd'),
            $employee->getLastName(),
            $employee->getFirstName(),
            $documentPrefix
        );
    }
}
