<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Department;
use App\Repository\EmployeeRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
    )
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $departments = [
            ['name' => 'HR', 'location' => 'Warszawa'],
            ['name' => 'IT', 'location' => 'Kraków'],
            ['name' => 'Marketing', 'location' => 'Gdańsk'],
            ['name' => 'Sprzedaż', 'location' => 'Wrocław'],
            ['name' => 'Finanse', 'location' => 'Poznań'],
        ];

        $employees = $this->employeeRepository->findAll();
        foreach ($departments as $data) {
            $randomIndex = rand(0, count($employees)-1);
            $department = new Department();
            $department->setName($data['name']);
            $department->setLocation($data['location']);
            $department->setManager($employees[$randomIndex]);
            $manager->persist($department);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EmployeeFixtures::class, 
        ];
    }
}
