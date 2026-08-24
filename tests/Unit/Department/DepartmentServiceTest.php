<?php

declare(strict_types=1);

namespace App\Tests\Unit\Department;

use App\Entity\Department;
use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Service\Department\DepartmentService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class DepartmentServiceTest extends TestCase
{
    public function testGetEmployeesInDepartmentsStatisticsWhenDepartmentExists(): void
    {
        $departmentRepository = $this->createMock(DepartmentRepository::class);
        $department1 = $this->createMock(Department::class);
        $employee1 = $this->createMock(Employee::class);
        $department1
            ->method('getName')
            ->willReturn('IT');

        $employee1
            ->method('isStatus')
            ->willReturn(true)
        ;

        $department1
            ->method('getEmployees')
            ->willReturn(new ArrayCollection([
                $employee1,
            ]));

        $departmentRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([
            $department1,
        ]);

        $departmentService = new DepartmentService($departmentRepository);
        [$labels, $employeeNumbers] = $departmentService->getEmployeesInDepartmentsStatistics();

        $this->assertCount(1, $labels);
        $this->assertSame(['IT'], $labels);

        $this->assertSame([1], $employeeNumbers);
    }

    public function testGetEmployeesInDepartmentStatisticsWhenDepartmentNotExists(): void
    {
        $departmentRepository = $this->createMock(DepartmentRepository::class);
        $departmentRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([])
        ;

        $departmentService = new DepartmentService($departmentRepository);
        [$labels, $employeeNumbers] = $departmentService->getEmployeesInDepartmentsStatistics();


        $this->assertSame([], $labels);
        $this->assertSame([], $employeeNumbers);
    }

    public function testGetEmployeesInDepartmentStatisticsWhenOneOfEmployeeIsDisabled(): void
    {
        $departmentRepository = $this->createMock(DepartmentRepository::class);
        $department1 = $this->createMock(Department::class);
        $department2 = $this->createMock(Department::class);

        $employee1 = $this->createMock(Employee::class);
        $employee2 = $this->createMock(Employee::class);
        $employee3 = $this->createMock(Employee::class);

        $department1
            ->method('getName')
            ->willReturn('IT');

        $department2
            ->method('getName')
            ->willReturn('Sales');

        $employee1
            ->method('isStatus')
            ->willReturn(true)
        ;

        $employee2
            ->method('isStatus')
            ->willReturn(false)
        ;

        $employee3
             ->method('isStatus')
             ->willReturn(true)
        ;

        $department1
            ->method('getEmployees')
            ->willReturn(new ArrayCollection([
                $employee1, $employee2,
            ]));

        
        $department2
            ->method('getEmployees')
            ->willReturn(new ArrayCollection([
                $employee3
            ]));

        $departmentRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([
            $department1, $department2,
        ]);

        $departmentService = new DepartmentService($departmentRepository);
        [$labels, $employeeNumbers] = $departmentService->getEmployeesInDepartmentsStatistics();

        $this->assertCount(2, $labels);
        $this->assertSame(['IT', 'Sales'], $labels);

        $this->assertCount(2, $employeeNumbers);
        $this->assertSame([1, 1], $employeeNumbers);

    }

    public function testGetEmployeesInDepartmentStatisticsWhenDepartmentDoesntContainAnyEmployees(): void
    {
        $departmentRepository = $this->createMock(DepartmentRepository::class);
        $department1 = $this->createMock(Department::class);

        $department1
            ->method('getName')
            ->willReturn('IT');

        $department1
            ->method('getEmployees')
            ->willReturn(new ArrayCollection([]));


        $departmentRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([
            $department1
        ]);

        $departmentService = new DepartmentService($departmentRepository);
        [$labels, $employeeNumbers] = $departmentService->getEmployeesInDepartmentsStatistics();

        $this->assertCount(1, $labels);
        $this->assertSame(['IT'], $labels);

        $this->assertCount(1, $employeeNumbers);
        $this->assertSame([0], $employeeNumbers);

    }

}
