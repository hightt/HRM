<?php
declare(strict_types=1);

namespace App\Repository;

use DateTime;
use App\Entity\WorkLog;
use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class WorkLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkLog::class);
    }


       public function findEmployeeWorkLogsByCurrentMonth(Employee $employee)
       {
            $firstDay = new DateTime('first day of this month');
            $lastDay = new DateTime('last day of this month');

           return $this->createQueryBuilder('w')
               ->where('w.date >= :firstDay')
               ->andWhere('w.date <= :lastDay')
               ->andWhere('w.employee = :employee')
               ->setParameter('firstDay', $firstDay)
               ->setParameter('lastDay', $lastDay)
               ->setParameter('employee', $employee)
               ->orderBy('w.date', 'ASC')
               ->setMaxResults(31)
               ->getQuery()
               ->execute()
           ;
       }

}
