<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class LocaleSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private LocaleAwareInterface $translator;

    public function __construct(RequestStack $requestStack, LocaleAwareInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $session = $this->requestStack->getSession();

        if ($request->get('lang')) {
            $locale = $request->get('lang');
            $session->set('_locale', $locale);
        } elseif ($session->has('_locale')) {
            $locale = $session->get('_locale');
        } else {
            $locale = $request->getDefaultLocale();
        }

        $request->setLocale($locale);
        $this->translator->setLocale($locale);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}