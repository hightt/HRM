<?php

declare(strict_types=1);

namespace App\Service\LeaveRequest;

use DateTimeImmutable;
use App\Entity\Employee;
use App\Entity\LeaveRequest;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LeaveRequestRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Model\LeaveRequest\LeaveRequestStatus;
use App\Model\LeaveRequest\LeaveRequestType;

class LeaveRequestService
{
    public const DEPARTMENT_HR_NAME = 'HR';

    public function __construct(
        private readonly EntityManagerInterface $entityManager, 
        private readonly LeaveRequestRepository $leaveRequestRepository,
        private readonly Security               $security, 
        private readonly DepartmentRepository $departmentRepository,
    )
    {}
    
    public function submit(LeaveRequest $leaveRequest)
    {
        /** @var User  currentEmployee */
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

    public function getAcceptingPerson(Employee $employee)
    {
        $manager = $employee->getDepartment()?->getManager();
        if ($manager?->getId() === $employee->getId()) {
            return $this->departmentRepository->findOneByName(self::DEPARTMENT_HR_NAME)?->getManager(); 
        }

        return $manager;
    }
}