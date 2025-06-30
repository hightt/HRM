<?php

declare(strict_types=1);

namespace App\Service\Email;

use App\Model\Email\EmailType;
use App\Model\Message\AbstractGenerateEmailMessage;

interface ReportEmailHandlerInterface
{
    public function supports(EmailType $emailType): bool;
    
    public function handle(AbstractGenerateEmailMessage $message): void;
}