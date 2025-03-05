<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Repository\WorkLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkLogRepository::class)]
class WorkLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'workLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $hour_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $hourEnd = null;

    #[ORM\Column]
    private ?float $hoursNumber = null;

    #[ORM\Column]
    private ?float $overtimeNumber = null;

    #[ORM\Column]
    private ?bool $isDayOff = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $absenceSymbol = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHourStart(): ?\DateTimeInterface
    {
        return $this->hour_start;
    }

    public function setHourStart(\DateTimeInterface $hour_start): static
    {
        $this->hour_start = $hour_start;

        return $this;
    }

    public function getHourEnd(): ?\DateTimeInterface
    {
        return $this->hourEnd;
    }

    public function setHourEnd(?\DateTimeInterface $hourEnd): static
    {
        $this->hourEnd = $hourEnd;

        return $this;
    }

    public function getHoursNumber(): ?float
    {
        return $this->hoursNumber;
    }

    public function setHoursNumber(float $hoursNumber): static
    {
        $this->hoursNumber = $hoursNumber;

        return $this;
    }

    public function getOvertimeNumber(): ?float
    {
        return $this->overtimeNumber;
    }

    public function setOvertimeNumber(float $overtimeNumber): static
    {
        $this->overtimeNumber = $overtimeNumber;

        return $this;
    }

    public function isDayOff(): ?bool
    {
        return $this->isDayOff;
    }

    public function setIsDayOff(bool $isDayOff): static
    {
        $this->isDayOff = $isDayOff;

        return $this;
    }

    public function getAbsenceSymbol(): ?string
    {
        return $this->absenceSymbol;
    }

    public function setAbsenceSymbol(?string $absenceSymbol): static
    {
        $this->absenceSymbol = $absenceSymbol;

        return $this;
    }
}
