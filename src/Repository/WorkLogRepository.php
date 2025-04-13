<?php
declare(strict_types=1);

namespace App\Repository;

use DateTime;
use App\Entity\WorkLog;
use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class WorkLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkLog::class);
    }


    public function findEmployeeWorkLogsByCurrentMonth(int $employeeId)
    {
        $firstDay = new DateTime('first day of this month');
        $lastDay = new DateTime('last day of this month');

        return $this->createQueryBuilder('w')
            ->where('w.date >= :firstDay')
            ->andWhere('w.date <= :lastDay')
            ->andWhere('w.employee = :employee')
            ->setParameter('firstDay', $firstDay->setTime(0, 0, 0))
            ->setParameter('lastDay', $lastDay->setTime(0, 0, 0))
            ->setParameter('employee', $employeeId)
            ->orderBy('w.date', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }

}
