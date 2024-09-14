<?php

declare(strict_types=1);

namespace App\Core\Presentation\Controller;

use App\Core\Domain\Model\Group;
use App\Core\Domain\Model\Profile;
use App\Core\Domain\Model\UserInterface;
use App\Core\Presentation\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        $user = $this->getUser();
        if ($user) {
            assert($user instanceof UserInterface);

            return $this->redirectToRoute(
                $user->isFirstLogin() ? 'app_user_choose_password' : 'app_user_profile',
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

    #[Route('/login_choose_password', name: 'app_user_choose_password')]
    public function loginChoosePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var null|UserInterface $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if (!$user->isFirstLogin()) {
            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->updatePassword($encodedPassword);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
