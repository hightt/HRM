<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employee;
use Psr\Log\LoggerInterface;
use App\Model\Email\EmailType;
use App\Form\MonthlyWorkLogType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TimeSheet\EmployeeTimeSheetService;
use App\Model\Message\GenerateEmployeeReportMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/time_sheet')]
final class TimeSheetController extends AbstractController
{
    #[Route('/employee/{id}', name: 'app_time_sheet_index', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function index(
        Request                  $request,
        Employee                 $employee,
        EmployeeTimeSheetService $employeeTimeSheetService,
        Security                 $security,
        TranslatorInterface      $translator
    ): Response {
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if ($currentUser->getEmployee()->getId() !== $employee->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ACCOUNTING');
        }

        $employeeWorkLogsInCurrentMonth = $employeeTimeSheetService->getEmployeeWorkLogsForCurrentMonth($employee);
        $form = $this->createForm(MonthlyWorkLogType::class, ['workLogs' => $employeeWorkLogsInCurrentMonth, 'employee' => $employee]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeTimeSheetService->saveTimeSheet($form->getData()['workLogs']);
            $this->addFlash('success', $translator->trans('time_sheet.actions.edit.success'));

            return $this->redirectToRoute('app_time_sheet_index', ['id' => $employee->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_sheet/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employee/{id}/generate_work_time_report_for_current_month', name: 'app_time_sheet_employee_generate_work_time_report_for_current_month', methods: [Request::METHOD_GET])]
    public function generateWorkTimeRaportForCurrentMonth(
        MessageBusInterface      $bus,
        Employee                 $employee,
        EmployeeTimeSheetService $employeeTimeSheetService,
        LoggerInterface          $logger,
        Security                 $security,
        TranslatorInterface      $translator
    ): Response {
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if ($currentUser->getEmployee()->getId() !== $employee->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ACCOUNTING');
        }

        $logger->info(sprintf('Starting generate montly work time report employee: %s [ID: %d]', $employee->getFullName(), $employee->getId()));

        $employeeReportMessage = new GenerateEmployeeReportMessage($employee->getUser()->getEmail(), $employee, EmailType::EMPLOYEE_MONTHLY_WORK_TIME_REPORT);
        $bus->dispatch($employeeReportMessage);
        $employeeWorkReportForCurrentMonth = $employeeTimeSheetService->getEmployeeMonthWorkReport($employee);
        $this->addFlash('success', $translator->trans('time_sheet.actions.generate_montly_work_report.success'));
        
        return $this->render('employee/show.html.twig', [
            'employee'   => $employee,
            'workReport' => $employeeWorkReportForCurrentMonth,
        ]);
    }

}
