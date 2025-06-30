<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\LeaveRequest;
use App\Form\LeaveRequestDecideFormType;
use App\Form\LeaveRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LeaveRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\LeaveRequest\LeaveRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/leave_request')]
final class LeaveRequestController extends AbstractController
{
    #[Route(name: 'app_leave_request_index', methods: [REQUEST::METHOD_GET])]
    public function index(): Response
    {
        return $this->render('leave_request/index.html.twig', []);
    }

    #[Route('/new', name: 'app_leave_request_new', methods: [REQUEST::METHOD_GET, REQUEST::METHOD_POST])]
    public function new(
        Request             $request,
        LeaveRequestService $leaveRequestService,
    ): Response {
        $leaveRequest = new LeaveRequest();
        $form = $this->createForm(LeaveRequestFormType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leaveRequestService->submit($leaveRequest);

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('leave_request/new.html.twig', [
            'leave_request' => $leaveRequest,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_leave_request_show', methods: [REQUEST::METHOD_GET])]
    public function show(LeaveRequest $leaveRequest): Response
    {
        return $this->render('leave_request/show.html.twig', [
            'leaveRequest' => $leaveRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_leave_request_edit', methods: [Request::METHOD_GET, REQUEST::METHOD_POST])]
    public function edit(
        Request                $request, 
        LeaveRequest           $leaveRequest, 
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(LeaveRequestFormType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('leave_request/new.html.twig', [
            'leave_request' => $leaveRequest,
            'form'          => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_delete', methods: [REQUEST::METHOD_POST])]
    public function delete(
        Request                $request, 
        LeaveRequest           $leaveRequest, 
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $leaveRequest->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($leaveRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/decide/{id}', name: 'app_leave_request_decide', methods: [Request::METHOD_GET, REQUEST::METHOD_POST])]
    public function decide(
        Request                $request, 
        LeaveRequest           $leaveRequest, 
        EntityManagerInterface $entityManager,
        Security               $security,
        LeaveRequestService     $leaveRequestService,
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if (!in_array($currentUser->getEmployee()->getId(), [$leaveRequest->getEmployee()->getId(), $leaveRequestService->getAcceptingPerson($leaveRequest->getEmployee())?->getId()])) {
            $this->denyAccessUnlessGranted('ROLE_ACCOUNTING');
        }

        $form = $this->createForm(LeaveRequestDecideFormType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('leave_request/decide.html.twig', [
            'leave_request' => $leaveRequest,
            'form'          => $form,
        ]);
    }


    #[Route('/list', name: 'app_leave_request_list', methods: [REQUEST::METHOD_GET])]
    public function list(
        LeaveRequestRepository $leaveRequestRepository,
        Request                $request,
        TranslatorInterface    $translatorInterface,
    ) {
        $draw = $request->query->getInt('draw');
        $start = $request->query->getInt('start', 0);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->all('search');
        $searchValue = isset($search['value']) ? $search['value'] : '';
        $queryBuilder = $leaveRequestRepository->createQueryBuilder('lq');

        $totalRecords = $queryBuilder
            ->select('COUNT(lq.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if (!empty($search)) {
            $queryBuilder
                ->where('lq.dateFrom LIKE :search OR lq.dateTo LIKE :search OR lq.status LIKE :search')
                ->setParameter('search', '%' . $searchValue . '%');
        }
        $recordsFiltered = $queryBuilder
            ->select('COUNT(lq.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $leaveRequests = $queryBuilder
            ->select('lq')
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->getQuery()
            ->getResult();


        $data = array_map(function ($leaveRequest) use ($translatorInterface) {
            return [
                'employeeName'          => $leaveRequest->getEmployee()->getFullName(),
                'reviewedByManagerName' => $leaveRequest->getReviewedBy()?->getFullName(),
                'leaveType'             => $leaveRequest->getLeaveType()->label($translatorInterface),
                'dateFrom'              => $leaveRequest->getDateFrom()->format('d.m.Y'),
                'dateTo'                => $leaveRequest->getDateTo()->format('d.m.Y'),
                'status'                => $leaveRequest->getStatus()->label($translatorInterface),
                'showUrl'               => $this->generateUrl('app_leave_request_show', ['id' => $leaveRequest->getId()]),
            ];
        }, $leaveRequests);

        return new JsonResponse([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
}
