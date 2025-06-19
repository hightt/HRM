<?php
declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LeaveRequestRepository;
use App\Model\LeaveRequest\LeaveRequestType;
use App\Model\LeaveRequest\LeaveRequestStatus;

#[ORM\Entity(repositoryClass: LeaveRequestRepository::class)]
class LeaveRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'leaveRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\Column(length: 255,  enumType: LeaveRequestType::class)]
    private ?LeaveRequestType $leaveType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateTo = null;

    #[ORM\Column(length: 255, enumType: LeaveRequestStatus::class)]
    private ?LeaveRequestStatus $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $managerComment = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reviewedLeaveRequests')]
    private ?Employee $reviewedBy = null;

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

    public function getLeaveType(): ?LeaveRequestType
    {
        return $this->leaveType;
    }

    public function setLeaveType(LeaveRequestType $leaveType): static
    {
        $this->leaveType = $leaveType;

        return $this;
    }

    public function getDateFrom(): ?DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(DateTimeInterface $dateFrom): static
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?DateTimeInterface
    {
        return $this->dateTo;
    }

    public function setDateTo(DateTimeInterface $dateTo): static
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    public function getStatus(): ?LeaveRequestStatus
    {
        return $this->status;
    }

    public function setStatus(LeaveRequestStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getManagerComment(): ?string
    {
        return $this->managerComment;
    }

    public function setManagerComment(?string $managerComment): static
    {
        $this->managerComment = $managerComment;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReviewedBy(): ?Employee
    {
        return $this->reviewedBy;
    }

    public function setReviewedBy(?Employee $reviewedBy): static
    {
        $this->reviewedBy = $reviewedBy;

        return $this;
    }
}
