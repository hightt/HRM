<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\WorkLog;
use App\Form\WorkLogType;
use App\Repository\WorkLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/time_sheet')]
final class TimeSheetController extends AbstractController
{
    #[Route('/employee/{id}', name: 'app_time_sheet_index', methods: ['GET'])]
    public function index(
        Employee            $employee,
        WorkLogRepository   $workLogRepository
    ): Response
    {
        return $this->render('time_sheet/index.html.twig', [
            'work_logs' => $workLogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_time_sheet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $workLog = new WorkLog();
        $form = $this->createForm(WorkLogType::class, $workLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($workLog);
            $entityManager->flush();

            return $this->redirectToRoute('app_time_sheet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_sheet/new.html.twig', [
            'work_log' => $workLog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_sheet_show', methods: ['GET'])]
    public function show(WorkLog $workLog): Response
    {
        return $this->render('time_sheet/show.html.twig', [
            'work_log' => $workLog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_time_sheet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WorkLog $workLog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkLogType::class, $workLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_time_sheet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_sheet/edit.html.twig', [
            'work_log' => $workLog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_sheet_delete', methods: ['POST'])]
    public function delete(Request $request, WorkLog $workLog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workLog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($workLog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_time_sheet_index', [], Response::HTTP_SEE_OTHER);
    }
}
