<?php
declare(strict_types = 1);

namespace App\Service\TimeSheet;

use DateTimeImmutable;
use IntlDateFormatter;

class WorkDaysService
{
    private array $holidays;

    public function __construct()
    {
        $this->holidays = ['01-01','01-06', '05-01', '05-03','08-15','11-01','11-11','12-25','12-26'];
    }

    public function getDaysOfMonth(int $year, int $month): array
    {
        $daysInMonth = (new DateTimeImmutable("$year-$month-01"))->format('t');
        $days = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = new DateTimeImmutable("$year-$month-$day");

            $isWeekend = $this->isWeekend($date);
            $isHoliday = $this->isHoliday($date);

            $days[] = [
                'date'      => $date->format('Y-m-d'),
                'dayOfWeek' => $this->getDayOfWeek($date),
                'isWeekend' => $isWeekend,
                'isHoliday' => $isHoliday,
            ];
        }

        return $days;
    }

    private function isWeekend(DateTimeImmutable $date): bool
    {
        return in_array($date->format('N'), [6, 7]);
    }

    private function isHoliday(DateTimeImmutable $date): bool
    {
        return in_array($date->format('m-d'), $this->holidays);
    }

    private function getDayOfWeek(DateTimeImmutable $date): string
    {
        $formatter = new IntlDateFormatter('pl_PL', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        return ucfirst($formatter->format($date));
    }
}
