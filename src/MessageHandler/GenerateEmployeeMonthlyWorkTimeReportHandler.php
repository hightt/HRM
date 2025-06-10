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
    /**
    * @param EmployeeEmailHandlerInterface[] $handlers
    */
    public function __construct(
        private MailerInterface                  $mailer,
        private WorkLogRepository                $workLogRepository,
        private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
        private Environment                      $twig, 
        private LoggerInterface                  $logger,
        private iterable                         $handlers,
    ) {}

    public function __invoke(GenerateEmployeeReportMessage $message)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($message->getEmployeeEmailType())) {
                $handler->handle($message);
                
                return;
            }
        }
    }
}
