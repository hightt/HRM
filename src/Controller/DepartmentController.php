<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use Psr\Log\LoggerInterface;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Model\Message\GenerateDepartmentReportMessage;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/department')]
final class DepartmentController extends AbstractController
{
    #[Route(name: 'app_department_index')]
    public function index(): Response
    {
        return $this->render('department/index.html.twig');
    }

    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/new', name: 'app_department_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface    $translator,
    ): Response {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('department.actions.create.success'));

            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('department/new.html.twig', [
            'department' => $department,
            'form'       => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_department_show', methods: ['GET'])]
    public function show(
        Department $department,
        Security   $security,
    ): Response {
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if ($currentUser->getEmployee()->getDepartment()->getId() !== $department->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ACCOUNTING');
        }

        $employees = array_filter($department->getEmployees()->toArray(), function($employee) {
            return $employee->isStatus() === true;
        });

        return $this->render('department/show.html.twig', [
            'department' => $department,
            'employees'  => $employees,
        ]);
    }


    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/{id}/edit', name: 'app_department_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Department             $department,
        EntityManagerInterface $entityManager,
        TranslatorInterface    $translator,
    ): Response {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('department.actions.edit.success'));

            return $this->redirectToRoute('app_department_index');
        }

        return $this->render('department/edit.html.twig', [
            'employee' => $department,
            'form'     => $form,
        ]);
    }

    #[IsGranted('ROLE_ACCOUNTING')]
    #[Route('/list', name: 'app_department_list', methods: ['GET'])]
    public function list(
        DepartmentRepository $departmentRepository,
        Request              $request,
    ) {
        $draw = $request->query->getInt('draw');
        $start = $request->query->getInt('start', 0);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->all('search');
        $searchValue = isset($search['value']) ? $search['value'] : '';
        $queryBuilder = $departmentRepository->createQueryBuilder('d');

        $totalRecords = $queryBuilder
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if (!empty($search)) {
            $queryBuilder
                ->where('d.name LIKE :search')
                ->setParameter('search', '%' . $searchValue . '%');
        }
        $recordsFiltered = $queryBuilder
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $departments = $queryBuilder
            ->select('d')
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->getQuery()
            ->getResult();

        $data = array_map(function ($department) {
            return [
                'name'        => $department->getName(),
                'managerName' => $department->getManagerName(),
                'location'    => $department->getLocation(),
                'showUrl'     => $this->generateUrl('app_department_show', ['id' => $department->getId()]),
            ];
        }, $departments);

        return new JsonResponse([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    #[Route('/department/{id}/generate_work_time_report_for_current_month', name: 'app_time_sheet_department_generate_work_time_report_for_current_month', methods: [Request::METHOD_GET])]
    public function generateWorkTimeReportForCurrentMonth(
        Department          $department,
        LoggerInterface     $logger,
        MessageBusInterface $bus,
        Security            $security,
        TranslatorInterface $translator,
    ): Response {
        $logger->info(sprintf('Starting generate montly work time report for department: %s [ID: %d]', $department->getName(), $department->getId()));
        
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        $bus->dispatch(new GenerateDepartmentReportMessage($currentUser->getEmail(), $department));
        $this->addFlash('success', $translator->trans('department.actions.generate_report.success'));
        
        return $this->render('department/show.html.twig', [
            'department' => $department,
        ]);
    }
}
