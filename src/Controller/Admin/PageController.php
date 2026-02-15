<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/page')]
class PageController extends AbstractController
{
    #[Route('/', name: 'admin_page_index', methods: ['GET'])]
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('admin/page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = new Page();
        
        if ($request->isMethod('POST')) {
            $page->setTitle($request->request->get('title'));
            $page->setSlug($request->request->get('slug'));
            $page->setLocale($request->request->get('locale', 'fr'));
            $page->setTemplate($request->request->get('template', 'default'));
            $page->setSeoTitle($request->request->get('seoTitle'));
            $page->setSeoDescription($request->request->get('seoDescription'));
            $page->setStatus($request->request->get('status', 'draft'));
            $page->setUpdatedAt(new \DateTime());

            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_index');
        }

        return $this->render('admin/page/new.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/{id}', name: 'admin_page_show', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->render('admin/page/show.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $page->setTitle($request->request->get('title'));
            $page->setSlug($request->request->get('slug'));
            $page->setLocale($request->request->get('locale'));
            $page->setTemplate($request->request->get('template'));
            $page->setSeoTitle($request->request->get('seoTitle'));
            $page->setSeoDescription($request->request->get('seoDescription'));
            $page->setStatus($request->request->get('status'));
            $page->setUpdatedAt(new \DateTime());

            $entityManager->flush();

            return $this->redirectToRoute('admin_page_index');
        }

        return $this->render('admin/page/edit.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/{id}', name: 'admin_page_delete', methods: ['POST'])]
    public function delete(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_page_index');
    }
}
