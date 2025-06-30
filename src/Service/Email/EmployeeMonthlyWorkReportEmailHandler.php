<?php

declare(strict_types=1);

namespace App\Service\Email;

use DateTime;
use Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use App\Model\Email\EmailType;
use Symfony\Component\Mime\Email;
use App\Repository\WorkLogRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use App\Model\Message\AbstractGenerateEmailMessage;
use App\Model\Message\GenerateEmployeeReportMessage;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('app.employee_email_handler')]
class EmployeeMonthlyWorkReportEmailHandler implements ReportEmailHandlerInterface
{
    public function __construct(
        private MailerInterface                  $mailer,
        private WorkLogRepository                $workLogRepository,
        private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
        private Environment                      $twig,
        private LoggerInterface                  $logger,
        private string $mailerFromAddress,
    ) {}

    public function supports(EmailType $type): bool
    {
        return $type === EmailType::EMPLOYEE_MONTHLY_WORK_TIME_REPORT;
    }

    public function handle(AbstractGenerateEmailMessage $message): void
    {
        if (!$message instanceof GenerateEmployeeReportMessage) {
            throw new InvalidArgumentException('Invalid message type for DepartmentMonthlyWorkReportEmailHandler');
        }

        try {
            $email = $message->getEmail();
            $employee = $message->getEmployee();
            $this->logger->info('Start processing employee report for: ' . $employee->getFullName());

            $employeeWorkLogsInCurrentMonth = $this->workLogRepository->findEmployeeWorkLogsByCurrentMonth($employee->getId());
            $documentName = $this->employeeDocumentGeneratorService->createDocumentName($employee, 'monthly_work_time_report');
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $html = $this->twig->render('pdf/employee_monthly_time_sheet.twig', [
                'date'     => new DateTime(),
                'workLogs' => $employeeWorkLogsInCurrentMonth,
                'employee' => $employee,
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();
            $filesystem = new Filesystem();
            $filePath = sys_get_temp_dir() . "/monthly_work_time_report.pdf";
            $filesystem->dumpFile($filePath, $pdfContent);

            $emailMessage = (new Email())
                ->from($this->mailerFromAddress)
                ->to($email)
                ->subject('Twój raport')
                ->text('Załączamy wygenerowany raport.')
                ->attachFromPath($filePath, $documentName, 'application/pdf');

            $this->mailer->send($emailMessage);

            $filesystem->remove($filePath);

            $this->logger->info('Employee report has been send to e-mail: ' . $email);
        } catch (Exception $e) {
            $this->logger->error("Error during generating monthly employee work time report. Exception: " . $e->getMessage());
        }
    }
}
