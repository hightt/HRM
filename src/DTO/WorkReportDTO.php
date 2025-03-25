<?php
declare(strict_types = 1);

namespace App\DTO;

class WorkReportDTO
{
    private float $overtimeHours;
    private float $workedHours;
    private int $absentDays;

    public function __construct(
        float   $workedHours, 
        float   $overtimeHours, 
        int     $absentDays
    )
    {
        $this->overtimeHours = $overtimeHours;
        $this->workedHours = $workedHours;
        $this->absentDays = $absentDays;
    }

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