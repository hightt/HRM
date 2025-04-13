<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\GenerateEmployeeReportMessage;
use DateTime;
use Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\WorkLogRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenerateEmployeeMonthlyWorkTimeReportHandler
{
    public function __construct(
        private MailerInterface                  $mailer,
        private WorkLogRepository                $workLogRepository,
        private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
        private Environment                      $twig, 
        private LoggerInterface                  $logger,
    ) {}

    public function __invoke(GenerateEmployeeReportMessage $message)
    {
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
            $filePath = sys_get_temp_dir() . "/$documentName.pdf";
            $filesystem->dumpFile($filePath, $pdfContent);

            $emailMessage = (new Email())
                ->from('noreply@yourdomain.com')
                ->to($email)
                ->subject('Twój raport')
                ->text('Załączamy wygenerowany raport.')
                ->attachFromPath($filePath, 'Raport.pdf', 'application/pdf');

            $this->mailer->send($emailMessage);

            $filesystem->remove($filePath);

            $this->logger->info('Employee report has been send to e-mail: ' . $email);
        } catch (Exception $e) {
            $this->logger->error("Error during generating monthly employee work time report. Exception: " . print_r($e));
            echo "❌ Error during generating monthly employee work: " . $e->getMessage() . "\n";
        }
    }
}
