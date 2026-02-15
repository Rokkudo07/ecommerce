<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findOneBy(['slug' => 'home', 'status' => 'published']);
        
        if (!$page) {
            return $this->render('page/default.html.twig');
        }

        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/{slug}', name: 'page_show', methods: ['GET'], requirements: ['slug' => '^(?!admin|login|logout|_profiler|_wdt).+'])]
    public function show(string $slug, PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findOneBy(['slug' => $slug, 'status' => 'published']);

        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }

        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }
}
