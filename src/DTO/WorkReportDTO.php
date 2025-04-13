<?php
declare(strict_types = 1);

namespace App\DTO;

class WorkReportDTO
{
    public function __construct(
        private float $workedHours, 
        private float $overtimeHours, 
        private int   $absentDays
    )
    {}

    public function getOvertimeHours(): float
    {
        return $this->overtimeHours;
    }

    public function getWorkedHours(): float
    {
        return $this->workedHours;
    }

    public function getAbsentDays(): int
    {
        return $this->absentDays;
    }
}