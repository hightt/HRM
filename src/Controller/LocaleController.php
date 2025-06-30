<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocaleController extends AbstractController
{
    #[Route('/set-locale/{locale}', name: 'set_locale')]
    public function setLocale(string $locale, Request $request, SessionInterface $session): RedirectResponse
    {
        $session->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?? '/');
    }
}
