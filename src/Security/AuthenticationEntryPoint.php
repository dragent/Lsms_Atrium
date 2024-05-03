<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('warning', 'Vous ne pouvez pas acceder à cettte page.');

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}