<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\EmployeeRepository;
use App\Repository\WorkLogRepository;
use App\Service\Department\DepartmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public static int $maxRecentJoinDays = 30;

    #[Route('/landing-page', name: 'app_landing_page')]
    public function landingPage(): Response
    {
        return $this->render('landing_page.html.twig', []);
    }

    #[Route('/', name: 'app_homecontroller')]
    public function index(
        EmployeeRepository $employeeRepository,
        WorkLogRepository  $workLogRepository,
        DepartmentService  $departmentService,
        Security           $security,
    ): Response {
        $numOfEmployees = count($employeeRepository->findBy(['status' => 1]));
        [$labels, $employeeNumbers] = $departmentService->getEmployeesInDepartmentsStatistics();

        /** @var User $currentUser */
        $currentUser = $security->getUser();

        return $this->render('dashboard.html.twig', [
            'numOfEmployees' => $numOfEmployees,
            'departmentChartData' => ['labels' => $labels, 'employeeNumbers' => $employeeNumbers],
            'lastJoinedEmployees' => $employeeRepository->getRecentlyJoinedEmployees(self::$maxRecentJoinDays),
            'employee' => $employeeRepository->findOneBy(['user' => $security->getUser()]),
            'workLogs' => $workLogRepository->findEmployeeWorkLogsByCurrentMonth($currentUser->getEmployee()->getId()),
        ]);
    }
}
