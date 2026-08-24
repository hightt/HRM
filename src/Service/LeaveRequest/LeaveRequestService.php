<?php

declare(strict_types=1);

namespace App\Service\LeaveRequest;

use App\Entity\Employee;
use App\Entity\LeaveRequest;
use App\Entity\User;
use App\Model\LeaveRequest\LeaveRequestStatus;
use App\Repository\DepartmentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class LeaveRequestService
{
    public const DEPARTMENT_HR_NAME = 'HR';

    public function __construct(
        private readonly EntityManagerInterface $entityManager, 
        private readonly Security               $security, 
        private readonly DepartmentRepository $departmentRepository,
    )
    {}
    
    public function submit(LeaveRequest $leaveRequest): void
    {
        /** @var User currentEmployee */
        $currentEmployee = $this->security->getUser();

        $leaveRequest
            ->setCreatedAt(new DateTimeImmutable())
            ->setStatus(LeaveRequestStatus::PENDING)
            ->setEmployee($currentEmployee->getEmployee())
            ->setReviewedBy($this->getAcceptingPerson($currentEmployee->getEmployee()))
        ;

        $this->entityManager->persist($leaveRequest);
        $this->entityManager->flush();

    }

    public function getAcceptingPerson(Employee $employee): ?Employee
    {
        $manager = $employee->getDepartment()?->getManager();
        if ($manager?->getId() === $employee->getId()) {
            return $this->departmentRepository->findOneByName(self::DEPARTMENT_HR_NAME)?->getManager(); 
        }

        return $manager;
    }
}