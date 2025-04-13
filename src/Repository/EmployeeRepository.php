<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

       public function getRecentlyJoinedEmployees(int $daysRange): array
       {
            $dateThreshold = new DateTime();
            $dateThreshold->modify("-{$daysRange} days");

           return $this->createQueryBuilder('e')
               ->andWhere('e.employmentDate >= :dateThreshold')
               ->andWhere('e.status = 1')
               ->setParameter('dateThreshold', $dateThreshold)
               ->orderBy('e.employmentDate', 'DESC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }
}
