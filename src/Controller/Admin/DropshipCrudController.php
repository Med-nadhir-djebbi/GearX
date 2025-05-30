<?php

namespace App\Controller\Admin;

use App\Entity\Dropship;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/dropships', name: 'admin_dropships_')]
class DropshipCrudController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $dropships = $this->entityManager->getRepository(Dropship::class)->findAll();

        return $this->render('admin/dropship/index.html.twig', [
            'dropships' => $dropships
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $dropship = new Dropship();
            $dropship->setName($request->request->get('name'));
            $dropship->setCompany($request->request->get('company'));
            $dropship->setEmail($request->request->get('email'));
            $dropship->setPhone($request->request->get('phone'));
            $dropship->setAddress($request->request->get('address'));
            $dropship->setStatus('active');

            $this->entityManager->persist($dropship);
            $this->entityManager->flush();

            $this->addFlash('success', 'Dropship created successfully.');
            return $this->redirectToRoute('admin_dropships_index');
        }

        return $this->render('admin/dropship/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dropship $dropship): Response
    {
        if ($request->isMethod('POST')) {
            $dropship->setName($request->request->get('name'));
            $dropship->setCompany($request->request->get('company'));
            $dropship->setEmail($request->request->get('email'));
            $dropship->setPhone($request->request->get('phone'));
            $dropship->setAddress($request->request->get('address'));
            $dropship->setStatus($request->request->get('status'));

            $this->entityManager->flush();

            $this->addFlash('success', 'Dropship updated successfully.');
            return $this->redirectToRoute('admin_dropships_index');
        }

        return $this->render('admin/dropship/edit.html.twig', [
            'dropship' => $dropship
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Dropship $dropship): Response
    {
        $this->entityManager->remove($dropship);
        $this->entityManager->flush();

        $this->addFlash('success', 'Dropship deleted successfully.');
        return $this->redirectToRoute('admin_dropships_index');
    }
} 