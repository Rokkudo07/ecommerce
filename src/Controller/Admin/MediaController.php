<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/media')]
class MediaController extends AbstractController
{
    #[Route('/', name: 'admin_media_index', methods: ['GET'])]
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('admin/media/index.html.twig', [
            'media' => $mediaRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_media_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $media = new Media();

        if ($request->isMethod('POST')) {
            $media->setPath($request->request->get('path'));
            $media->setAlt($request->request->get('alt'));

            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('admin_media_index');
        }

        return $this->render('admin/media/new.html.twig', [
            'media' => $media,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_media_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Media $media, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $media->setPath($request->request->get('path'));
            $media->setAlt($request->request->get('alt'));

            $entityManager->flush();

            return $this->redirectToRoute('admin_media_index');
        }

        return $this->render('admin/media/edit.html.twig', [
            'media' => $media,
        ]);
    }

    #[Route('/{id}', name: 'admin_media_delete', methods: ['POST'])]
    public function delete(Request $request, Media $media, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$media->getId(), $request->request->get('_token'))) {
            $entityManager->remove($media);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_media_index');
    }
}
