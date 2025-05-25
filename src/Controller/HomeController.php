<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    static int $maxRecentJoinDays = 30;

    #[Route('/landing-page', name: 'app_landing_page')]
    public function landingPage(): Response {
        return $this->render('landing_page.html.twig', []);
    }

    #[Route('/', name: 'app_homecontroller')]
    public function index(
        EmployeeRepository       $employeeRepository,
        DepartmentRepository     $departmentRepository,
        Security                 $security,
    ): Response {
        $numOfEmployees = count($employeeRepository->findBy(['status' => 1]));
        $labels = [];
        $employeeNumbers = [];
        foreach ($departmentRepository->findAll() as $department) {
            $activeEmployees = array_filter($department->getEmployees()->toArray(), function ($employee) {
                return true === $employee->isStatus();
            });

            $labels[] = $department->getName();
            $employeeNumbers[] = count($activeEmployees);
        }

        return $this->render('dashboard.html.twig', [
            'numOfEmployees'      => $numOfEmployees,
            'departmentChartData' => ['labels' => $labels, 'employeeNumbers' => $employeeNumbers],
            'lastJoinedEmployees' => $employeeRepository->getRecentlyJoinedEmployees(self::$maxRecentJoinDays),
            'employee'            => $employeeRepository->findOneBy(['user' => $security->getUser()])
        ]);
    }
}
