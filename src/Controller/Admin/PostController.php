<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\PostRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/posts', name: 'app_admin_post_')]
final class PostController extends AbstractController
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
    ) {
    }

    #[Route(name: 'index')]
    public function indexAction(Request $request): Response
    {
        $posts = $this->postRepository->findLatest($request->request->getInt('page', 1));

        return $this->render('admin/post/index.html.twig', ['posts' => $posts]);
    }
}
