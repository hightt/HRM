<?php

declare(strict_types=1);

namespace App\tests\MessageHandler;

use App\Entity\Employee;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use App\Model\LeaveRequest\EmployeeEmail;
use App\Service\Email\LeaveRequestEmailSendHandler;
use App\Model\Message\GenerateEmployeeReportMessage;
use App\Service\Email\MonthlyWorkReportEmailHandler;
use App\MessageHandler\GenerateEmployeeEmailHandler;

class GenerateEmployeeEmailHandlerTest extends TestCase
{
    public function testCorrectHandlerIsCalled(): void
    {
        $message = $this->createMock(GenerateEmployeeReportMessage::class);
        $message->method('getEmployee')->willReturn($this->createMock(Employee::class));
        $message->method('getEmployeeEmailType')->willReturn(EmployeeEmail::MONTHLY_WORK_TIME_REPORT);
        $message->method('getEmail')->willReturn('test@o2.pl');

        $handlers = [];
        $monthlyWorkReportEmailHandler = $this->createMock(MonthlyWorkReportEmailHandler::class);
        $monthlyWorkReportEmailHandler->method('supports')
            ->with(EmployeeEmail::MONTHLY_WORK_TIME_REPORT)
            ->willReturn(true);
        $monthlyWorkReportEmailHandler->expects($this->once())
            ->method('handle')
            ->with($message);

        $leaveRequestEmailSendHandler = $this->createMock(LeaveRequestEmailSendHandler::class);
        $leaveRequestEmailSendHandler->method('supports')
            ->with(EmployeeEmail::LEAVE_REQUEST_SUBMIT)
            ->willReturn(true);

        $handlers = [
            $monthlyWorkReportEmailHandler,
            $leaveRequestEmailSendHandler,
        ];

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with('Handled by: ' . get_class($monthlyWorkReportEmailHandler));

        $handler = new GenerateEmployeeEmailHandler($logger, $handlers);

        ($handler)($message);
    }

    public function testAnyHanlderWasNotCalled(): void
    {
        $message = $this->createMock(GenerateEmployeeReportMessage::class);
        $message->method('getEmployee')->willReturn($this->createMock(Employee::class));
        $message->method('getEmployeeEmailType')->willReturn(EmployeeEmail::MONTHLY_WORK_TIME_REPORT);
        $message->method('getEmail')->willReturn('test@o2.pl');

        $handlers = [];

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('info');
        $handler = new GenerateEmployeeEmailHandler($logger, $handlers);

        ($handler)($message);
    }
}
