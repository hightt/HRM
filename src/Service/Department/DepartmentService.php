<?php
declare(strict_types = 1);

namespace App\Service\Department;

use App\Repository\DepartmentRepository;

class DepartmentService
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
    )
    {}
    
    public function getEmployeesInDepartmentsStatisitcs(): array
    {
        foreach ($this->departmentRepository->findAll() as $department) {
            $activeEmployees = array_filter($department->getEmployees()->toArray(), function ($employee) {
                return true === $employee->isStatus();
            });

            $labels[] = $department->getName();
            $employeeNumbers[] = count($activeEmployees);
        }

        return [$labels, $employeeNumbers];
    }
}
