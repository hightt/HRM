<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homecontroller')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        EmployeeRepository $employeeRepository,
    ): Response {

        return $this->render('dashboard.html.twig', []);
    }
}
