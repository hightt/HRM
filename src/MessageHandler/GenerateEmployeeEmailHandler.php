<?php
declare(strict_types=1);

namespace App\MessageHandler;

use Twig\Environment;
use Psr\Log\LoggerInterface;
use App\Repository\WorkLogRepository;
use Symfony\Component\Mailer\MailerInterface;
use App\Model\Message\GenerateEmployeeReportMessage;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsMessageHandler]
class GenerateEmployeeEmailHandler
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
                $this->logger->info("Handled by: " . get_class($handler));

                return;
            }
        }
    }
}
