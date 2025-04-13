<?php
declare(strict_types = 1);

namespace App\Service\Employee;

use App\Entity\User;
use App\DTO\EmployeeDTO;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateEmployeeAndUserService 
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createEmployeeAndUserAfterFormSend(EmployeeDTO $employeeDTO)
    {
        $employeeEntity = $this->createEmployeeEntity($employeeDTO);
        $userEntity = $this->createUserEntityWithDefaultPassword($employeeDTO, $employeeEntity->getPesel());
        $employeeEntity->setUser($userEntity);

        $this->entityManager->flush();
    }

    private function createEmployeeEntity(EmployeeDTO $employeeDTO): Employee
    {
        $employee = (new Employee())
            ->setFirstName($employeeDTO->getFirstName())
            ->setLastName($employeeDTO->getLastName())
            ->setBirthdayDate($employeeDTO->getBirthdayDate())
            ->setPesel($employeeDTO->getPesel())
            ->setEmploymentDate($employeeDTO->getEmploymentDate())
            ->setPosition($employeeDTO->getPosition())
            ->setPhoneNumber($employeeDTO->getPhoneNumber())
            ->setAddress($employeeDTO->getAddress())
            ->setSalary($employeeDTO->getSalary())
            ->setStatus(true)
            ->setGender($employeeDTO->getGender())
            ->setDepartment($employeeDTO->getDepartment())
        ;
        $this->entityManager->persist($employee);

        return $employee;
    }

    private function createUserEntityWithDefaultPassword(EmployeeDTO $employeeDTO, string $pesel): User
    {
        $user = new User();
        $user->setEmail($employeeDTO->getEmail());

        $hashedPassword = $this->passwordHasher->hashPassword($user, $pesel);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);

        return $user;
    }

}
