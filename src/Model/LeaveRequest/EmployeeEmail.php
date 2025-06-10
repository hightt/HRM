<?php

declare(strict_types=1);

namespace App\Service\Email;

enum EmployeeEmail: string
{
    case MONTHLY_WORK_TIME_REPORT = 'monthly_work_time_report';
    case LEAVE_REQUEST_SUBMIT = 'leave_request_submit';
}