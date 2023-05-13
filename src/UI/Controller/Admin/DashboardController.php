<?php

declare(strict_types=1);

namespace App\UI\Controller\Admin;

use App\Repository\AdminUserRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin', )]
final class DashboardController extends AbstractController
{
    public function __construct(
        private readonly AdminUserRepositoryInterface $adminUserRepository,
        private readonly PostRepositoryInterface $postRepository,
    ) {
    }

    #[Route(name: 'app_admin_dashboard')]
    public function indexAction(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'adminUsersCount' => $this->adminUserRepository->countAll(),
            'postsCount' => $this->postRepository->countAll(),
        ]);
    }
}
