<?php

declare(strict_types=1);

namespace App\Service\Email;

use Twig\Environment;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\WorkLogRepository;
use App\Model\LeaveRequest\EmployeeEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Model\Message\GenerateEmployeeReportMessage;
use App\Service\Employee\EmployeeDocumentGeneratorService;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('app.employee_email_handler')]
class LeaveRequestEmailSendHandler implements EmployeeEmailHandlerInterface
{
    public function __construct(
        private MailerInterface                  $mailer,
        private WorkLogRepository                $workLogRepository,
        private EmployeeDocumentGeneratorService $employeeDocumentGeneratorService,
        private Environment                      $twig,
        private LoggerInterface                  $logger,
        private string $mailerFromAddress,
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
            ->from($this->mailerFromAddress)
            ->to($email)
            ->subject('Twój raport')
            ->text('Załączamy wygenerowany raport.');

        $this->mailer->send($emailMessage);
    }
}
