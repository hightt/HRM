<?php

declare(strict_types=1);

namespace App\tests;

use Datetime;
use DateTimeImmutable;
use App\Entity\WorkLog;
use ReflectionProperty;
use App\Entity\Employee;
use App\Entity\AbsenceSymbol;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use App\Repository\WorkLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\TimeSheet\WorkDaysService;
use PHPUnit\Framework\MockObject\MockObject;
use App\Service\TimeSheet\EmployeeTimeSheetService;

class EmployeeTimeSheetServiceTest extends TestCase
{
    /** @var WorkLogRepository&MockObject */
    private WorkLogRepository $workLogRepository;

    /** @var WorkDaysService&MockObject */
    private WorkDaysService $workDaysService;

    /** @var EntityManagerInterface&MockObject */
    private EntityManagerInterface $entityManagerInterface;

    private EmployeeTimeSheetService $service;

    protected function setUp(): void
    {
        $this->workLogRepository = $this->createMock(WorkLogRepository::class);
        $this->workDaysService   = $this->createMock(WorkDaysService::class);
        $this->entityManagerInterface = $this->createMock(EntityManager::class);

        $this->service = new EmployeeTimeSheetService(
            $this->workDaysService,
            $this->workLogRepository,
            $this->entityManagerInterface
        );
    }

    public function testReturnExistingEmployeeWorkLogsForCurrentMonth()
    {
        $employee = $this->createEmployee();
        $workLogs = [
            (new WorkLog())
            ->setDate(new Datetime())
            ->setEmployee($employee)
            ->setHourStart(new Datetime('08:00'))
            ->setHourEnd(new Datetime('16:00'))
            ->setHoursNumber(8),
            (new WorkLog())
            ->setDate(new Datetime())
            ->setAbsenceSymbol(new AbsenceSymbol())
        ];

        $this->workLogRepository
            ->method('findEmployeeWorkLogsByCurrentMonth')
            ->with($employee->getId())
            ->willReturn($workLogs)
        ;

        $employeeWorkLogsForCurrentMonth = $this->service->getEmployeeWorkLogsForCurrentMonth($employee);

        $this->assertCount(2, $employeeWorkLogsForCurrentMonth);
        $this->assertIsBool($employeeWorkLogsForCurrentMonth[1]->isDayOff());
        $this->assertIsNotBool($employeeWorkLogsForCurrentMonth[0]->isDayOff());
    }

    public function testReturnNotExistingEmployeeWorkLogsForCurrentMonth()
    {
        $employee = $this->createEmployee();
        $daysOfMonth = [
            ['date' => '2025-04-01', 'isWeekend' => false, 'isHoliday' => false],
            ['date' => '2025-04-05', 'isWeekend' => true, 'isHoliday' => false],
            ['date' => '2025-04-06', 'isWeekend' => false, 'isHoliday' => true,]
        ];


        $this->workLogRepository
            ->method('findEmployeeWorkLogsByCurrentMonth')
            ->with($employee->getId())
            ->willReturn([])
        ;

        $this->workDaysService
            ->expects($this->once())
            ->method('getDaysOfMonth')
            ->with((int)date('Y'), (int)(date('m')))
            ->willReturn($daysOfMonth);

            $employeeWorkLogsForCurrentMonth = $this->service->getEmployeeWorkLogsForCurrentMonth($employee);
            $this->assertCount(3, $employeeWorkLogsForCurrentMonth);
            $this->assertIsBool($employeeWorkLogsForCurrentMonth[1]->isDayOff());
            $this->assertTrue($employeeWorkLogsForCurrentMonth[1]->isDayOff());
            $this->assertFalse($employeeWorkLogsForCurrentMonth[0]->isDayOff());

            $weekendLog = $employeeWorkLogsForCurrentMonth[0];
            $this::assertFalse($weekendLog->isDayOff());
            $this::assertSame(8.00, $weekendLog->getHoursNumber());
            $this::assertEquals(new DateTimeImmutable('2025-04-01'), $weekendLog->getDate());
            $this::assertNull($weekendLog->getAbsenceSymbol());
    }

    public function testEmployeeMonthWorkReportForExistingWorkLogs()
    {
        $employee = $this->createEmployee();

        $workLogs = [
            (new WorkLog())
            ->setDate(new Datetime('2024-04-01'))
            ->setHoursNumber(8.00)
            ->setOvertimeNumber(0)
            ->setAbsenceSymbol(null),
            (new WorkLog())
            ->setDate(new Datetime('2024-04-02'))
            ->setHoursNumber(0)
            ->setOvertimeNumber(0)
            ->setAbsenceSymbol(new AbsenceSymbol()),
            (new WorkLog())
            ->setDate(new Datetime('2024-04-03'))
            ->setHoursNumber(10.50)
            ->setOvertimeNumber(2.50)
            ->setAbsenceSymbol(null),
            (new WorkLog())
            ->setDate(new Datetime('2024-04-04'))
            ->setHoursNumber(9.50)
            ->setOvertimeNumber(1.50)
            ->setAbsenceSymbol(null),
        ];

        $this->workLogRepository
            ->expects($this->once())
            ->method('findEmployeeWorkLogsByCurrentMonth')
            ->willReturn($workLogs)
        ;

        /** @var WorkReportDTO $employeeMonthWorkReport */
        $employeeMonthWorkReport = $this->service->getEmployeeMonthWorkReport($employee);
        $this->assertEquals(28.00, $employeeMonthWorkReport->getWorkedHours());
        $this->assertEquals(4.00, $employeeMonthWorkReport->getOvertimeHours());
        $this->assertEquals(1, $employeeMonthWorkReport->getAbsentDays());
    }

    public function testEmployeeMonthWorkReportForNotExistingWorkLogs()
    {
        $employee = $this->createEmployee();

        $this->workLogRepository
            ->expects($this->once())
            ->method('findEmployeeWorkLogsByCurrentMonth')
            ->willReturn([])
        ;

        /** @var WorkReportDTO $employeeMonthWorkReport */
        $employeeMonthWorkReport = $this->service->getEmployeeMonthWorkReport($employee);
        $this->assertEquals(0.00, $employeeMonthWorkReport->getWorkedHours());
        $this->assertEquals(0.00, $employeeMonthWorkReport->getOvertimeHours());
        $this->assertEquals(0.00, $employeeMonthWorkReport->getAbsentDays());
    }

    public function createEmployee(): Employee
    {
        $employee = (new Employee())
        ->setFirstName('Jan')
        ->setLastName('Kowalski')
        ;   
        $refl = new ReflectionProperty(Employee::class, 'id');
        $refl->setAccessible(true);
        $refl->setValue($employee, 1);

        return $employee;
    }
}
