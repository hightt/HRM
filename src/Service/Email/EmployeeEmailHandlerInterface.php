<?php

declare(strict_types=1);

namespace App\Service\Email;

use App\Message\GenerateEmployeeReportMessage;

interface EmployeeEmailHandlerInterface
{
    public function supports(EmployeeEmail $employeeEmail): bool;
    
    public function handle(GenerateEmployeeReportMessage $message): void;
}