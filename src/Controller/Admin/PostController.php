<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Application\Handler\PostHandlerInterface;
use App\DTO\UpdatePost;
use App\Form\PostType;
use App\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

#[Route(path: '/admin/posts', name: 'app_admin_post_')]
final class PostController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PostHandlerInterface $postHandler,
        private readonly PostRepositoryInterface $postRepository,
    ) {
    }

    #[Route(name: 'index', methods: [Request::METHOD_GET])]
    public function indexAction(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->postRepository->findLatestPaginated($page);

        if (!$pagination->hasPage($page)) {
            return $this->redirectToRoute('app_admin_post_index', ['page' => $pagination->getPagesTotal()]);
        }

        return $this->render('admin/post/index.html.twig', ['pagination' => $pagination]);
    }

    #[Route(path: '/create', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(PostType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            Assert::isInstanceOf($dto, UpdatePost::class);

            $post = $this->postHandler->create($dto);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('admin/post/create.html.twig', ['form' => $form]);
    }

    #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [Request::METHOD_GET, Request::METHOD_PUT])]
    public function updateAction(Request $request, int $id): Response
    {
        $post = $this->postRepository->findOneBy(['id' => $id]) ?? throw $this->createNotFoundException();

        $form = $this->createForm(PostType::class, UpdatePost::fromEntity($post), ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            Assert::isInstanceOf($dto, UpdatePost::class);

            $this->postHandler->update($post, $dto);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('admin/post/update.html.twig', ['form' => $form, 'post' => $post]);
    }

    #[Route(path: '/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: [Request::METHOD_DELETE])]
    public function deleteAction(int $id): Response
    {
        $post = $this->postRepository->findOneBy(['id' => $id]) ?? throw $this->createNotFoundException();

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_admin_post_index');
    }
}
