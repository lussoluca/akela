<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Security;

use App\Core\Infrastructure\Persistence\Repository\UserRepository;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class AppAuthenticator.
 */
class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    /**
     * AppAuthenticator constructor.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected UrlGeneratorInterface $urlGenerator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
    ) {}

    public function authenticate(Request $request): Passport
    {
        $fiscal_code = $request->request->get('email');
        $password = $request->request->get('password');
        $csrfToken = $request->request->get('_csrf_token');

        try {
            Assertion::string($fiscal_code);
            Assertion::string($password);
            Assertion::nullOrString($csrfToken);
        } catch (AssertionFailedException $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return new Passport(
            new UserBadge($fiscal_code),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('authenticate', $csrfToken)]
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        // If there is a target path in the session, perform a redirect after
        // a successful login.
        if ($targetPath = $this->getTargetPath(
            $request->getSession(),
            $firewallName
        )) {
            return new RedirectResponse($targetPath);
        }

        return null;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
