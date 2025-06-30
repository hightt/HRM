<?php
declare(strict_types = 1);

namespace App\Service\Employee;

use App\Entity\User;
use App\Entity\Employee;
use App\Event\UserCreatedEvent;
use App\Model\Employee\EmployeeModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateEmployeeAndUserService 
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface    $eventDispatcher,
    ) {}

    public function createEmployeeAndUserAfterFormSend(EmployeeModel $employeeModel): void
    {
        $employeeEntity = $this->createEmployeeEntity($employeeModel);
        $userEntity = $this->createUserEntityWithDefaultPassword($employeeModel, $employeeEntity->getPesel());
        $employeeEntity->setUser($userEntity);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new UserCreatedEvent($userEntity));
    }

    private function createEmployeeEntity(EmployeeModel $employeeModel): Employee
    {
        $employee = (new Employee())
            ->setFirstName($employeeModel->getFirstName())
            ->setLastName($employeeModel->getLastName())
            ->setBirthdayDate($employeeModel->getBirthdayDate())
            ->setPesel($employeeModel->getPesel())
            ->setEmploymentDate($employeeModel->getEmploymentDate())
            ->setPosition($employeeModel->getPosition())
            ->setPhoneNumber($employeeModel->getPhoneNumber())
            ->setAddress($employeeModel->getAddress())
            ->setSalary($employeeModel->getSalary())
            ->setStatus(true)
            ->setGender($employeeModel->getGender())
            ->setDepartment($employeeModel->getDepartment())
        ;
        $this->entityManager->persist($employee);

        return $employee;
    }

    private function createUserEntityWithDefaultPassword(EmployeeModel $employeeModel, string $pesel): User
    {
        $user = new User();
        $user->setEmail($employeeModel->getEmail());

        $hashedPassword = $this->passwordHasher->hashPassword($user, $pesel);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);

        return $user;
    }

}
