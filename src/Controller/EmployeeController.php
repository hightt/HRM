<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Model\Employee\EmployeeModel;
use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Form\NewEmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\TimeSheet\EmployeeTimeSheetService;
use App\Service\Employee\CreateEmployeeAndUserService;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/employee')]
final class EmployeeController extends AbstractController
{
    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route(name: 'app_employee_index', methods: ['GET'])]
    public function index(): Response {

        return $this->render('employee/index.html.twig', []);
    }

    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(
        Request                      $request,
        CreateEmployeeAndUserService $createEmployeeAndUserService,
        TranslatorInterface          $translator,
    ): Response {
        $employeeModel = new EmployeeModel();
        $form = $this->createForm(NewEmployeeType::class, $employeeModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createEmployeeAndUserService->createEmployeeAndUserAfterFormSend($employeeModel);
            $this->addFlash('success', $translator->trans('employee.actions.create.success'));

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/show/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(
        EmployeeTimeSheetService $employeeTimeSheetService,
        Employee                 $employee,
        Security                 $security,
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if ($currentUser->getEmployee()->getId() !== $employee->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ACCOUNTING');
        }
        
        $employeeWorkReportForCurrentMonth = $employeeTimeSheetService->getEmployeeMonthWorkReport($employee);

        return $this->render('employee/show.html.twig', [
            'employee'   => $employee,
            'workReport' => $employeeWorkReportForCurrentMonth,
        ]);
    }

    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Employee               $employee,
        EntityManagerInterface $entityManager,
        TranslatorInterface    $translator,
    ): Response {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('employee.actions.edit.success'));
            
            return $this->redirectToRoute('app_employee_edit', ['id' => $form->getData()->getId()]);
        }

        return $this->render('employee/edit.html.twig', [
            'employee'  => $employee,
            'form'      => $form,
        ]);
    }

    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(
        Request                 $request,
        Employee                $employee,
        EntityManagerInterface  $entityManager,
        TranslatorInterface     $translator,
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($employee);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('employee.actions.delete.success'));
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/list', name: 'app_employee_list', methods: ['GET'])]
    public function list(
        EmployeeRepository  $employeeRepository,
        Request             $request,
    ) {
        $draw = $request->query->getInt('draw');
        $start = $request->query->getInt('start', 0);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->all('search');
        $searchValue = isset($search['value']) ? $search['value'] : '';
        $queryBuilder = $employeeRepository->createQueryBuilder('e');

        $totalRecords = $queryBuilder
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if (!empty($search)) {
            $queryBuilder
                ->where('e.firstName LIKE :search OR e.lastName LIKE :search OR e.position LIKE :search')
                ->setParameter('search', '%' . $searchValue . '%');
        }
        $recordsFiltered = $queryBuilder
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $employees = $queryBuilder
            ->select('e')
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->getQuery()
            ->getResult();

        $data = array_map(function ($employee) {
            return [
                'id'           => $employee->getId(),
                'name'         => sprintf('%s %s', $employee->getFirstName(), $employee->getLastName()),
                'position'     => $employee->getPosition(),
                'department'   => $employee->getDepartment()?->getName(),
                'showUrl'      => $this->generateUrl('app_employee_show', ['id' => $employee->getId()]),
            ];
        }, $employees);

        return new JsonResponse([
            'draw'              => $draw,
            'recordsTotal'      => $totalRecords,
            'recordsFiltered'   => $recordsFiltered,
            'data'              => $data,
        ]);
    }
}
