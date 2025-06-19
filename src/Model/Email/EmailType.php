<?php

declare(strict_types=1);

namespace App\Model\Email;

enum EmailType: string
{
    case EMPLOYEE_MONTHLY_WORK_TIME_REPORT = 'employee_monthly_work_time_report';
    case DEPARTMENT_MONTHLY_WORK_TIME_REPORT = 'department_leave_request_submit';
    case LEAVE_REQUEST_SUBMIT = 'leave_request_submit';
}