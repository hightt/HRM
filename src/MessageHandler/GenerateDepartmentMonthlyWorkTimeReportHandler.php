<?php
declare(strict_types=1);

namespace App\MessageHandler;

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
use App\Service\TimeSheet\EmployeeTimeSheetService;
use App\Model\Message\GenerateDepartmentReportMessage;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenerateDepartmentMonthlyWorkTimeReportHandler
{
    public function __construct(
        private MailerInterface                  $mailer,
        private WorkLogRepository                $workLogRepository,
        private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
        private Environment                      $twig, 
        private LoggerInterface                  $logger,
        private EmployeeTimeSheetService         $employeeTimeSheetService,
    ) {}

    public function __invoke(GenerateDepartmentReportMessage $message)
    {
        try {
            $department = $message->getDepartment();
            $departmentEmployees = $department->getEmployees();
            $employeesWorkTime = [];

            foreach ($departmentEmployees as $i => $departmentEmployee) {
                $employeesWorkTime[$i] = $this->employeeTimeSheetService->getEmployeeMonthlyWorkTimeSummary($departmentEmployee->getId());
                $employeesWorkTime[$i]['employee'] =  $departmentEmployee;
            }
            
            $options = (new Options())
                ->set('defaultFont', 'DejaVu Sans')
                ->set('isHtml5ParserEnabled', true)
            ;
            $dompdf = new Dompdf($options);
            $html = $this->twig->render('pdf/department_monthly_time_sheet_of_employees.twig', [
                'date'             => new DateTime(),
                'employeeWorkLogs' => $employeesWorkTime,
                'department'       => $department,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();
            $filesystem = new Filesystem();
            $filePath = sys_get_temp_dir() . "/department.pdf";
            $filesystem->dumpFile($filePath, $pdfContent);

            $emailMessage = (new Email())
                ->from('noreply@yourdomain.com')
                ->to($message->getEmail())
                ->subject('Twój raport')
                ->text('Załączamy wygenerowany raport.')
                ->attachFromPath($filePath, 'department_report.pdf', 'application/pdf')
            ;
            $this->mailer->send($emailMessage);

            $filesystem->remove($filePath);
            $this->logger->info('Department report has been send to e-mail: ' . $message->getEmail());
        } catch (Exception $e) {
            $this->logger->error("Error during generating monthly employee work time report. Exception: " . $e->getMessage());
            echo "❌ Error during generating department report: " . $e->getMessage() . "\n";
        }
    }
}
