<?php

declare(strict_types=1);

namespace App\Service\Email;


use App\Model\LeaveRequest\EmployeeEmail;
use App\Model\Message\GenerateEmployeeReportMessage;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

interface EmployeeEmailHandlerInterface
{
    public function supports(EmployeeEmail $employeeEmail): bool;
    
    public function handle(GenerateEmployeeReportMessage $message): void;
}