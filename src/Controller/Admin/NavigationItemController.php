<?php

namespace App\Controller\Admin;

use App\Entity\NavigationItem;
use App\Repository\NavigationItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/navigation')]
class NavigationItemController extends AbstractController
{
    #[Route('/', name: 'admin_navigation_index', methods: ['GET'])]
    public function index(NavigationItemRepository $repository): Response
    {
        return $this->render('admin/navigation/index.html.twig', [
            'items' => $repository->findAllOrdered(),
        ]);
    }

    #[Route('/new', name: 'admin_navigation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, NavigationItemRepository $repository): Response
    {
        $item = new NavigationItem();

        if ($request->isMethod('POST')) {
            $item->setLabel($request->request->get('label'));
            $item->setType($request->request->get('type'));
            $item->setUrl($request->request->get('url'));
            $item->setPosition((int) $request->request->get('position', 0));
            $item->setIsExternal($request->request->get('isExternal') === '1');
            $item->setIsVisible($request->request->get('isVisible') !== '0');

            $parentId = $request->request->get('parent');
            if ($parentId) {
                $parent = $repository->find($parentId);
                if ($parent) {
                    $item->setParent($parent);
                }
            }

            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('admin_navigation_index');
        }

        return $this->render('admin/navigation/new.html.twig', [
            'item' => $item,
            'allItems' => $repository->findAllOrdered(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_navigation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NavigationItem $item, EntityManagerInterface $entityManager, NavigationItemRepository $repository): Response
    {
        if ($request->isMethod('POST')) {
            $item->setLabel($request->request->get('label'));
            $item->setType($request->request->get('type'));
            $item->setUrl($request->request->get('url'));
            $item->setPosition((int) $request->request->get('position', 0));
            $item->setIsExternal($request->request->get('isExternal') === '1');
            $item->setIsVisible($request->request->get('isVisible') !== '0');

            $parentId = $request->request->get('parent');
            if ($parentId && $parentId != $item->getId()) {
                $parent = $repository->find($parentId);
                if ($parent) {
                    $item->setParent($parent);
                }
            } elseif (!$parentId) {
                $item->setParent(null);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_navigation_index');
        }

        return $this->render('admin/navigation/edit.html.twig', [
            'item' => $item,
            'allItems' => $repository->findAllOrdered(),
        ]);
    }

    #[Route('/{id}', name: 'admin_navigation_delete', methods: ['POST'])]
    public function delete(Request $request, NavigationItem $item, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_navigation_index');
    }

    #[Route('/{id}/toggle-visibility', name: 'admin_navigation_toggle_visibility', methods: ['POST'])]
    public function toggleVisibility(NavigationItem $item, EntityManagerInterface $entityManager): Response
    {
        $item->setIsVisible(!$item->isVisible());
        $entityManager->flush();

        return $this->redirectToRoute('admin_navigation_index');
    }
}
