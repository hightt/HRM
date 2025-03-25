<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/department')]
final class DepartmentController extends AbstractController
{
    #[Route(name: 'app_department_index')]
    public function index(): Response
    {
        return $this->render('department/index.html.twig');
    }

    #[Route('/new', name: 'app_department_new', methods: ['GET', 'POST'])]
    public function new(
        Request                 $request,
        EntityManagerInterface  $entityManager,
    ): Response {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
            $this->addFlash('success', sprintf('Pomyślnie dodano dział: %s', $department->getName()));

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
    ): Response {
        return $this->render('department/show.html.twig', [
            'department'   => $department,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_department_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                 $request,
        Department              $department,
        EntityManagerInterface  $entityManager,
    ): Response {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
            $this->addFlash('success', 'Pomyślnie edytowano dane działu');

            return $this->redirectToRoute('app_department_index');
        }

        return $this->render('department/edit.html.twig', [
            'employee'  => $department,
            'form'      => $form,
        ]);
    }

    #[Route('/list', name: 'app_department_list', methods: ['GET'])]
    public function list(
        DepartmentRepository    $departmentRepository,
        Request                 $request,
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
                'id'          =>  $department->getId(),
                'name'        =>  $department->getName(),
                'managerName' =>  $department->getManagerName(),
                'location'    =>    $department->getLocation(),
                'editUrl'     => $this->generateUrl('app_department_edit', ['id' => $department->getId()]),
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
}
