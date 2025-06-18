<?php

declare(strict_types=1);

namespace App\tests\MessageHandler;

use Twig\Environment;
use App\Entity\Employee;
use App\Entity\Department;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Email;
use App\Repository\WorkLogRepository;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\TimeSheet\EmployeeTimeSheetService;
use App\Model\Message\GenerateDepartmentReportMessage;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use App\MessageHandler\GenerateDepartmentMonthlyWorkTimeReportHandler;

class GenerateDepartmentMonthlyWorkTimeReportHandlerTest extends TestCase
{
    /** @var WorkLogRepository&MockObject */
    private WorkLogRepository $workLogRepository;

    /** @var EmployeeDocumentGeneratorService&MockObject */
    private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService;

    /** @var MailerInterface&MockObject */
    private MailerInterface $mailer;

    /** @var Environment&MockObject */
    private Environment $twig;

    /** @var LoggerInterface&MockObject */
    private LoggerInterface $logger;

    /** @var EmployeeTimeSheetService&MockObject */
    private EmployeeTimeSheetService $timeSheetService;

    /** @var GenerateDepartmentMonthlyWorkTimeReportHandler&MockObject */
    private GenerateDepartmentMonthlyWorkTimeReportHandler $handler;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->workLogRepository = $this->createMock(WorkLogRepository::class);
        $this->employeeDocumentGeneratorService = $this->createMock(EmployeeDocumentGeneratorService::class);
        $this->twig = $this->createMock(Environment::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->timeSheetService = $this->createMock(EmployeeTimeSheetService::class);

        $this->handler = new GenerateDepartmentMonthlyWorkTimeReportHandler(
            $this->mailer,
            $this->workLogRepository,
            $this->employeeDocumentGeneratorService,
            $this->twig,
            $this->logger,
            $this->timeSheetService
        );
    }

    public function testMonthlyWorkTimeReportGeneration(): void
    {
        $employee = $this->createMock(Employee::class);
        $employee->method('getId')->willReturn(1);

        $department = $this->createMock(Department::class);
        $department->method('getEmployees')->willReturn(new ArrayCollection([$employee]));

        $message = $this->createMock(GenerateDepartmentReportMessage::class);
        $message->method('getDepartment')->willReturn($department);
        $message->method('getEmail')->willReturn('test@example.com');

        $this->timeSheetService->method('getEmployeeMonthlyWorkTimeSummary')
            ->with(1)
            ->willReturn([
                'sumHoursNumber' => 160,
                'overtimeSum' => 10,
                'sumAbsenceDays' => 2,
            ]);

        $this->twig->method('render')->willReturn('<html>PDF</html>');

        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) {
                return
                    $email->getTo()[0]->getAddress() === 'test@example.com' &&
                    $email->getSubject() === 'Twój raport';
            }));

        ($this->handler)($message);
    }
}
