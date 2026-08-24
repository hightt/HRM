<?php

declare(strict_types=1);

namespace App\Service\Email;

use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use App\Model\Email\EmailType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Model\Message\AbstractGenerateEmailMessage;
use App\Model\Message\GenerateEmployeeReportMessage;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('app.employee_email_handler')]
class LeaveRequestEmailSendHandler implements ReportEmailHandlerInterface
{
    public function __construct(
        private MailerInterface                  $mailer,
        private LoggerInterface                  $logger,
        private string $mailerFromAddress,
    ) {}

    public function supports(EmailType $type): bool
    {
        return $type === EmailType::LEAVE_REQUEST_SUBMIT;
    }

    public function handle(AbstractGenerateEmailMessage $message): void
    {
        if (!$message instanceof GenerateEmployeeReportMessage) {
            throw new InvalidArgumentException('Invalid message type for DepartmentMonthlyWorkReportEmailHandler');
        }

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
