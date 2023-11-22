<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return ($request->attributes->get("_route") === "security_login" && $request->isMethod("POST"));
    }

    public function authenticate(Request $request): Passport
    {
        $params = $request->request->all()['login'];
        $userName = $params['user_name'];
        $password = $params["password"];
        $passport = new Passport(
            new UserBadge($userName),
            new PasswordCredentials($password)
        );
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse("/");
        
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
        return null;
    }
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
       return new RedirectResponse("/login");
   }    
}
