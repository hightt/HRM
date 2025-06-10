<?php

declare(strict_types=1);

namespace App\Service\Email;

use App\Message\GenerateEmployeeReportMessage;
use Twig\Environment;
use Psr\Log\LoggerInterface;
use App\Repository\WorkLogRepository;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\Mime\Email;

class LeaveRequestSubmitted implements EmployeeEmailHandlerInterface
{
    public function __construct(
        private MailerInterface                  $mailer,
        private WorkLogRepository                $workLogRepository,
        private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
        private Environment                      $twig,
        private LoggerInterface                  $logger,
    ) {}

    public function supports(EmployeeEmail $type): bool
    {
        return $type === EmployeeEmail::LEAVE_REQUEST_SUBMIT;
    }

    public function handle(GenerateEmployeeReportMessage $message): void
    {
        $email = $message->getEmail();
        $employee = $message->getEmployee();
        $this->logger->info('Start processing employee report for: ' . $employee->getFullName());

        $emailMessage = (new Email())
            ->from('noreply@yourdomain.com')
            ->to($email)
            ->subject('Twój raport')
            ->text('Załączamy wygenerowany raport.');

        $this->mailer->send($emailMessage);
    }
}
