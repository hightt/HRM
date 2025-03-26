<?php
declare(strict_types = 1);

namespace App\DataFixtures;

use App\Repository\EmployeeRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\DepartmentRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class EmployeeDepartmentFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private EmployeeRepository   $employeeRepository,
        private DepartmentRepository $departmentRepository,
    ){}

    public function load(ObjectManager $manager): void
    {
        $employees = $this->employeeRepository->findAll();
        $departments = $this->departmentRepository->findAll();
        foreach ($departments as $i => $department) {
            $department->setManager($employees[$i]);
            $manager->persist($department);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['2'];
    }
}
