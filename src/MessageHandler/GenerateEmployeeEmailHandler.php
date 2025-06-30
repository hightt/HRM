<?php
declare(strict_types=1);

namespace App\MessageHandler;

use Psr\Log\LoggerInterface;
use App\Model\Message\GenerateEmployeeReportMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenerateEmployeeEmailHandler
{
    /**
    * @param ReportEmailHandlerInterface[] $handlers
    */
    public function __construct(
        private LoggerInterface                  $logger,
        private iterable                         $handlers,
    ) {}

    public function __invoke(GenerateEmployeeReportMessage $message)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($message->getEmailType())) {
                $handler->handle($message);
                $this->logger->info("Handled by: " . get_class($handler));

                return;
            }
        }
    }
}
