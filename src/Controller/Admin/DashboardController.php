<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin', )]
final class DashboardController extends AbstractController
{
    #[Route(name: 'app_admin_dashboard')]
    public function indexAction(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
