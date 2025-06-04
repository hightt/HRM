<?php
declare(strict_types = 1);

namespace App\Entity;

use DateTimeInterface;
use App\Entity\LeaveRequest;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $birthdayDate = null;

    #[ORM\Column(length: 11)]
    private ?string $pesel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $employmentDate = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column]
    private ?float $salary = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(length: 1)]
    private ?string $gender = null;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Department $department = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, WorkLog>
     */
    #[ORM\OneToMany(targetEntity: WorkLog::class, mappedBy: 'employee')]
    private Collection $workLogs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contractType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $amountOfWorkingTime = null;

    /**
     * @var Collection<int, LeaveRequest>
     */
    #[ORM\OneToMany(targetEntity: LeaveRequest::class, mappedBy: 'employee')]
    private Collection $leaveRequests;

    /**
     * @var Collection<int, LeaveRequest>
     */
    #[ORM\OneToMany(targetEntity: LeaveRequest::class, mappedBy: 'reviewedBy')]
    private Collection $reviewedLeaveRequests;

    public function __construct()
    {
        $this->workLogs = new ArrayCollection();
        $this->leaveRequests = new ArrayCollection();
        $this->reviewedLeaveRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthdayDate(): ?DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(DateTimeInterface $birthdayDate): static
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(string $pesel): static
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getEmploymentDate(): ?DateTimeInterface
    {
        return $this->employmentDate;
    }

    public function setEmploymentDate(DateTimeInterface $employmentDate): static
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, WorkLog>
     */
    public function getWorkLogs(): Collection
    {
        return $this->workLogs;
    }

    public function addWorkLog(WorkLog $workLog): static
    {
        if (!$this->workLogs->contains($workLog)) {
            $this->workLogs->add($workLog);
            $workLog->setEmployee($this);
        }

        return $this;
    }

    public function removeWorkLog(WorkLog $workLog): static
    {
        if ($this->workLogs->removeElement($workLog)) {
            // set the owning side to null (unless already changed)
            if ($workLog->getEmployee() === $this) {
                $workLog->setEmployee(null);
            }
        }

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(?string $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getAmountOfWorkingTime(): ?string
    {
        return $this->amountOfWorkingTime;
    }

    public function setAmountOfWorkingTime(?string $amountOfWorkingTime): static
    {
        $this->amountOfWorkingTime = $amountOfWorkingTime;

        return $this;
    }

    /**
     * @return Collection<int, LeaveRequest>
     */
    public function getLeaveRequests(): Collection
    {
        return $this->leaveRequests;
    }

    public function addLeaveRequest(LeaveRequest $leaveRequest): static
    {
        if (!$this->leaveRequests->contains($leaveRequest)) {
            $this->leaveRequests->add($leaveRequest);
            $leaveRequest->setEmployee($this);
        }

        return $this;
    }

    public function removeLeaveRequest(LeaveRequest $leaveRequest): static
    {
        if ($this->leaveRequests->removeElement($leaveRequest)) {
            // set the owning side to null (unless already changed)
            if ($leaveRequest->getEmployee() === $this) {
                $leaveRequest->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LeaveRequest>
     */
    public function getReviewedLeaveRequests(): Collection
    {
        return $this->reviewedLeaveRequests;
    }

    public function addReviewedLeaveRequest(LeaveRequest $reviewedLeaveRequest): static
    {
        if (!$this->reviewedLeaveRequests->contains($reviewedLeaveRequest)) {
            $this->reviewedLeaveRequests->add($reviewedLeaveRequest);
            $reviewedLeaveRequest->setReviewedBy($this);
        }

        return $this;
    }

    public function removeReviewedLeaveRequest(LeaveRequest $reviewedLeaveRequest): static
    {
        if ($this->reviewedLeaveRequests->removeElement($reviewedLeaveRequest)) {
            // set the owning side to null (unless already changed)
            if ($reviewedLeaveRequest->getReviewedBy() === $this) {
                $reviewedLeaveRequest->setReviewedBy(null);
            }
        }

        return $this;
    }

}
