<?php

declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Employee;
use App\Form\MonthlyWorkLogType;
use App\Repository\WorkLogRepository;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TimeSheet\EmployeeTimeSheetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/time_sheet')]
final class TimeSheetController extends AbstractController
{
    #[Route('/employee/{id}', name: 'app_time_sheet_index', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function index(
        Request                  $request,
        Employee                 $employee,
        EmployeeTimeSheetService $employeeTimeSheetService,
    ): Response {
        $employeeWorkLogsInCurrentMonth = $employeeTimeSheetService->getEmployeeWorkLogsForCurrentMonth($employee);

        $form = $this->createForm(MonthlyWorkLogType::class, ['workLogs' => $employeeWorkLogsInCurrentMonth, 'employee' => $employee]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employeeTimeSheetService->saveTimeSheet($form->getData()['workLogs']);
            $this->addFlash('success', 'Pomyślnie edytowano dane ewidencje czasu pracy');

            return $this->redirectToRoute('app_time_sheet_index', ['id' => $employee->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_sheet/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employee/{id}/generate_work_time_raport_for_current_month', name: 'app_time_sheet_employee_generate_work_time_raport_for_current_month', methods: [Request::METHOD_GET])]
    public function generateWorkTimeRaportForCurrentMonth(
        Employee                         $employee,
        WorkLogRepository                $workLogRepository,
        EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
    ): Response {
        $employeeWorkLogsInCurrentMonth = $workLogRepository->findEmployeeWorkLogsByCurrentMonth($employee);
        $documentName = $employeeDocumentGeneratorService->createDocumentName($employee, 'monthly_work_time_report');
        $options = (new Options())
            ->set('defaultFont', 'DejaVu Sans')
            ->set('isHtml5ParserEnabled', true)
        ;

        $dompdf = new Dompdf($options);
        $html = $this->renderView('pdf/pdf_template.html.twig', [
            'title' => 'Raport Pracowników',
            'date' => new DateTime(),
            'workLogs' => $employeeWorkLogsInCurrentMonth,
            'employee' => $employee,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => sprintf('inline; filename="%s.pdf"', $documentName),
        ]);
        
    }
}
