<?php

declare(strict_types=1);

namespace App\Model\Employee;

use App\Entity\Department;
use App\Entity\User;
use DateTimeInterface;

class EmployeeModel
{
    private ?int $id = null;

    private ?float $salary = null;

    private ?string $email = null;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $pesel = null;
    private ?string $position = null;
    private ?string $phoneNumber = null;
    private ?string $address = null;
    private ?string $gender = null;

    private ?Department $department = null;
    private ?User $user = null;

    private ?DateTimeInterface $birthdayDate = null;
    private ?DateTimeInterface $employmentDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(?string $pesel): self
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBirthdayDate(): ?DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(?DateTimeInterface $birthdayDate): self
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getEmploymentDate(): ?DateTimeInterface
    {
        return $this->employmentDate;
    }

    public function setEmploymentDate(?DateTimeInterface $employmentDate): self
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }
}