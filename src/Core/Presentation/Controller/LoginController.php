<?php

declare(strict_types=1);

namespace App\Core\Presentation\Controller;

use App\Core\Domain\Model\UserInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        $user = $this->getUser();
        if ($user) {
            assert($user instanceof UserInterface);

            return $this->redirectToRoute(
                'app_user_profile',
                ['id' => $user->getId()]
            );
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        if (null != $error) {
            switch (get_class($error)) {
                case BadCredentialsException::class:
                case CustomUserMessageAuthenticationException::class:
                    $this->addFlash(
                        LogLevel::ERROR,
                        'Le credenziali non sono corrette'
                    );

                    break;

                default:
                    $this->addFlash(
                        LogLevel::ERROR,
                        $error->getMessage()
                    );

                    break;
            }
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'user/auth/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
