<?php

namespace App\Controller\Admin;

use App\Entity\Block;
use App\Entity\Page;
use App\Repository\BlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/block')]
class BlockController extends AbstractController
{
    #[Route('/page/{pageId}', name: 'admin_block_index', methods: ['GET'])]
    public function index(int $pageId, BlockRepository $blockRepository, EntityManagerInterface $entityManager): Response
    {
        $page = $entityManager->getRepository(Page::class)->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }

        $blocks = $blockRepository->findBy(['page' => $page], ['position' => 'ASC']);

        return $this->render('admin/block/index.html.twig', [
            'blocks' => $blocks,
            'page' => $page,
        ]);
    }

    #[Route('/page/{pageId}/new', name: 'admin_block_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $pageId, EntityManagerInterface $entityManager): Response
    {
        $page = $entityManager->getRepository(Page::class)->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }

        $block = new Block();
        $block->setPage($page);

        if ($request->isMethod('POST')) {
            $block->setType($request->request->get('type'));
            $payload = json_decode($request->request->get('payload', '{}'), true);
            $block->setPayload($payload ?: []);
            $block->setPosition((int) $request->request->get('position', 0));

            $entityManager->persist($block);
            $entityManager->flush();

            return $this->redirectToRoute('admin_block_index', ['pageId' => $pageId]);
        }

        return $this->render('admin/block/new.html.twig', [
            'block' => $block,
            'page' => $page,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_block_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Block $block, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $block->setType($request->request->get('type'));
            $payload = json_decode($request->request->get('payload', '{}'), true);
            $block->setPayload($payload ?: []);
            $block->setPosition((int) $request->request->get('position', 0));

            $entityManager->flush();

            return $this->redirectToRoute('admin_block_index', ['pageId' => $block->getPage()->getId()]);
        }

        return $this->render('admin/block/edit.html.twig', [
            'block' => $block,
            'page' => $block->getPage(),
        ]);
    }

    #[Route('/{id}', name: 'admin_block_delete', methods: ['POST'])]
    public function delete(Request $request, Block $block, EntityManagerInterface $entityManager): Response
    {
        $pageId = $block->getPage()->getId();

        if ($this->isCsrfTokenValid('delete'.$block->getId(), $request->request->get('_token'))) {
            $entityManager->remove($block);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_block_index', ['pageId' => $pageId]);
    }
}
