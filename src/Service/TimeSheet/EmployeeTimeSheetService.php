<?php
declare(strict_types=1);

namespace App\Service\TimeSheet;

use App\DTO\WorkReportDTO;
use App\Entity\Employee;
use DateTimeImmutable;
use App\Entity\WorkLog;
use App\Repository\WorkLogRepository;
use App\Service\TimeSheet\WorkDaysService;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class EmployeeTimeSheetService
{
    public function __construct(
        private WorkDaysService        $workDaysService,
        private WorkLogRepository      $workLogRepository,
        private EntityManagerInterface $entityManagerInterface,
    ) {}

    public function getEmployeeWorkLogsForCurrentMonth(Employee $employee): array
    {
        $currentDate = new Datetime();
        $employeeWorkLogsInCurrentMonth = $this->workLogRepository->findEmployeeWorkLogsByCurrentMonth($employee);
        
        foreach ($employeeWorkLogsInCurrentMonth as $employeeWorkLog) {
            if (!is_null($employeeWorkLog->getAbsenceSymbol()) && $employeeWorkLog->isDayOff() !== true) {
                $employeeWorkLog->setIsDayOff = true;
            }
        }

        if (!empty($employeeWorkLogsInCurrentMonth)) {
            return $employeeWorkLogsInCurrentMonth;
        }

        $daysInMonth = $this->workDaysService->getDaysOfMonth((int)$currentDate->format('Y'), (int)$currentDate->format('m'));
        foreach ($daysInMonth as $dayInMonth) {
            $defaultValueIsDayOff = $dayInMonth['isHoliday'] || $dayInMonth['isWeekend'] ? true : false;
            $workLog = new WorkLog();
            $workLog
                ->setDate(new DateTimeImmutable($dayInMonth['date']))
                ->setEmployee($employee)
                ->setHourStart(new DateTimeImmutable('8:00'))
                ->setHourEnd(new DateTimeImmutable('16:00'))
                ->setHoursNumber(8.00)
                ->setOvertimeNumber(0)
                ->setIsDayOff($defaultValueIsDayOff)
                ->setAbsenceSymbol(null)
            ;

            $employeeWorkLogsInCurrentMonth[] = $workLog;
        }

        return $employeeWorkLogsInCurrentMonth;
    }

    public function saveTimeSheet(array $workLogs)
    {
        /** @var WorkLog $workLog */
        foreach ($workLogs as $workLog) {
            if (!is_null($workLog->getAbsenceSymbol()) || $workLog->isDayOff()) {
                $this->resetWorkLogData($workLog);
                $this->entityManagerInterface->persist($workLog);

                continue;
            }

            $hourDatetimeDifference = $workLog->getHourEnd()?->diff($workLog->getHourStart());
            if (!is_null($hourDatetimeDifference)) {
                $hoursDifferenceInMinutes = $hourDatetimeDifference->h * 60 + $hourDatetimeDifference->i;
                $hoursDifferenceInHours = $hoursDifferenceInMinutes / 60;
                $workLog->setHoursNumber($hoursDifferenceInHours);
                $workLog->setOvertimeNumber($hoursDifferenceInHours > 8 ? $hoursDifferenceInHours - 8 : null);
            }

            $this->entityManagerInterface->persist($workLog);
        }
        $this->entityManagerInterface->flush();
    }

    private function resetWorkLogData(WorkLog $workLog)
    {
        $workLog
            ->setHourStart(null)
            ->setHourEnd(null)
            ->setIsDayOff(true)
            ->setHoursNumber(null)
            ->setOvertimeNumber(null)
        ;
        $this->entityManagerInterface->persist($workLog);

        return $workLog;
    }

    public function getEmployeeMonthWorkReport(Employee $employee): WorkReportDTO
    {
        $employeeWorkLogsInCurrentMonth = $this->workLogRepository->findEmployeeWorkLogsByCurrentMonth($employee);

        if (empty($employeeWorkLogsInCurrentMonth)) {
            return new WorkReportDTO(0, 0, 0);
        }

        $workedHours = 0;
        $overtimeHours = 0;
        $absentDays = 0;

        foreach ($employeeWorkLogsInCurrentMonth as $workLogInCurrentMonth) {
            $workedHours   += $workLogInCurrentMonth->getHoursNumber();
            $overtimeHours += $workLogInCurrentMonth->getOvertimeNumber();
            $absentDays    += $workLogInCurrentMonth->getAbsenceSymbol() ? 1 : 0;
        }

        return new WorkReportDTO($workedHours, $overtimeHours, $absentDays);
    }
}
