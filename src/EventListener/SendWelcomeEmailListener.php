<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use App\Event\UserCreatedEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Twig\Environment;

#[AsEventListener]
class SendWelcomeEmailListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private UrlGeneratorInterface    $urlGeneratorInterface,
        private LoggerInterface          $loggerInterface,
        private Environment              $twig
    ) {}

    public function __invoke(UserCreatedEvent $event): void
    {
        $user = $event->getUser();
        $loginUrl = $this->urlGeneratorInterface->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);
        
        $email = (new Email())
        ->from(new Address('noreply@your-hrm-app.com', 'HRM App'))
        ->to($user->getEmail())
        ->subject('welcome_email.subject!')
        ->html($this->twig->render('emails/welcome_email.html.twig', [
            'user' => $user,
            'loginUrl' => $loginUrl,
        ]));

        $this->mailer->send($email);
        $this->loggerInterface->info('SendWelcomeEmailListener triggered. Welcome e-mail has been send to: ' . $user->getEmail());
    }
}
