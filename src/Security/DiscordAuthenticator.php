<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class DiscordAuthenticator extends AbstractAuthenticator
{
    private const DISCORD_AUTH_KEY = "discord-auth";

    public function __construct(private readonly UserRepository $userRepository, private readonly RouterInterface $router)
    {}
    
    public function supports(Request $request): bool|null
    {
        return $request->attributes->get('_route')==='app_discord_auth' && $this->isValidRequest($request);
    }

    public function authenticate(Request $request): Passport
    {
        if(!$this->isValidRequest($request))
            throw new AuthenticationException("La requête n'est pas valide");
        $discordId = $request->query->get('discordId');
        if(!$discordId)
            throw new AuthenticationException("Le token a été perdu");
        $user=$this->userRepository->findOneBy(['discordId'=>$discordId]);
        if(!$user)
            throw new AuthenticationException("Le token n'est pas valide");
        $userBadge = new UserBadge($user->getUserIdentifier(), function() use ($user){
            return $user;
        });

        return new SelfValidatingPassport($userBadge);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response|null
    {
        /** @var Session $session */
        $session = $request->getSession();
        $session->remove(self::DISCORD_AUTH_KEY);
        $session->getFlashBag()->set('danger', $exception->getMessage());
        return new RedirectResponse($this->router->generate("app_index"));
        //
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response|null
    {
        $request->getSession()->remove(self::DISCORD_AUTH_KEY);
        return null;
        //
    }

    private function isValidRequest(Request $request)
    {
        return true === $request->getSession()->get(self::DISCORD_AUTH_KEY);
    }
}
