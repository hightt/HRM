<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
    ): Response
{   
        $employee = new Employee();
        $form = $this->createForm(EmployeeEditType::class, $employee);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $entityManagerInterface->persist($employee);
           $entityManagerInterface->flush();

           return $this->redirectToRoute('app_employee');
       }

       return $this->render('employee_edit.html.twig', [
           'form' => $form->createView(),
       ]);
    }
}
