<?php

declare(strict_types=1);

namespace App\UI\Controller\Admin;

use App\Domain\Entity\AdminUserInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/admin')]
final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
    ) {
    }

    #[Route(path: '/login', name: 'app_admin_login')]
    public function loginAction(): Response
    {
        $user = $this->getUser();

        if ($user instanceof AdminUserInterface) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    #[Route(path: '/logout', name: 'app_admin_logout')]
    public function logoutAction(): never
    {
        throw new RuntimeException('This action should be handle by Symfony Security component');
    }
}
