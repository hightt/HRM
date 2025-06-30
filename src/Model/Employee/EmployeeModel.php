<?php
declare(strict_types = 1);

namespace App\Model\Employee;

use App\Entity\User;
use DateTimeInterface;
use App\Entity\Department;

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
    private ?User $userId = null;

    private ?DateTimeInterface $birthdayDate = null;
    private ?DateTimeInterface $employmentDate = null;

    public function getEmploymentDate()
    {
        return $this->employmentDate;
    }

    public function setEmploymentDate($employmentDate)
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }

    public function getBirthdayDate()
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate($birthdayDate)
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getPesel()
    {
        return $this->pesel;
    }

    public function setPesel($pesel)
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
