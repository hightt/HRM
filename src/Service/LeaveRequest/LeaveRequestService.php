<?php

declare(strict_types=1);

namespace App\Service\LeaveRequest;

use App\Entity\LeaveRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LeaveRequestRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Model\LeaveRequest\LeaveRequestStatus;

class LeaveRequestService
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private LeaveRequestRepository $leaveRequestRepository,
        private Security               $security, 
    )
    {}
    
    public function submit(LeaveRequest $leaveRequest)
    {
        /** @var User  currentEmployee */
        $currentEmployee = $this->security->getUser();

        $leaveRequest
            ->setCreatedAt(new \DateTimeImmutable())
            ->setStatus(LeaveRequestStatus::PENDING)
            ->setEmployee($currentEmployee->getEmployee())
        ;

        $this->entityManager->persist($leaveRequest);
        $this->entityManager->flush();
    }
}