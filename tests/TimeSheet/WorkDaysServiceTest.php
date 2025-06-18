<?php

declare(strict_types=1);

namespace App\tests;

use App\Service\TimeSheet\WorkDaysService;
use PHPUnit\Framework\TestCase;

class WorkDaysServiceTest extends TestCase
{
    private WorkDaysService $workDaysService;

    public function setUp(): void
    {
        $this->workDaysService = new WorkDaysService();
    }

    public function testGetDaysOfMonthReturnCorrectStructure()
    {
        $year = 2025;
        $month = 4;
        $daysOfMonth = $this->workDaysService->getDaysOfMonth($year, $month);
        $numOfDays = count($daysOfMonth);

        $this->assertIsArray($daysOfMonth, 'Check if array is returned');
        $this->assertArrayHasKey(29, $daysOfMonth, 'Check that elements of array is correct for april 2025');
        $this->assertEquals(30, $numOfDays);

        foreach ($daysOfMonth as $dayOfMonth) {
            $this->assertArrayHasKey('date', $dayOfMonth);
            $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}/', $dayOfMonth['date']);
            $this->assertIsString($dayOfMonth['date']);

            $this->assertArrayHasKey('dayOfWeek', $dayOfMonth);
            $this->assertIsString($dayOfMonth['dayOfWeek']);

            $this->assertArrayHasKey('isHoliday', $dayOfMonth);
            $this->assertIsBool($dayOfMonth['isHoliday']);

            $this->assertArrayHasKey('isWeekend', $dayOfMonth);
            $this->assertIsBool($dayOfMonth['isWeekend']);
        }
    }

    public function testHolidayOrWeekendDetection()
    {
        $year = 2025;
        $month = 1;
        $daysOfMonth = $this->workDaysService->getDaysOfMonth($year, $month);
        $firstDayOfMonth = $daysOfMonth[0];

        $this->assertTrue($firstDayOfMonth['isHoliday']);
        $this->assertFalse($firstDayOfMonth['isWeekend']);

        $this->assertTrue($daysOfMonth[3]['isWeekend']);
        $this->assertFalse($daysOfMonth[3]['isHoliday']);
    }
}
