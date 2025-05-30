<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/products', name: 'admin_products_')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractController
{
    private $entityManager;
    private $productRepository;
    private $categoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager, 
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/products.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $product = new Product();
            $product->setName($request->request->get('name'));
            $product->setDescription($request->request->get('description'));
            $product->setPrice((float)$request->request->get('price'));
            $product->setStock((int)$request->request->get('stock'));
            
            // Set category
            if ($categoryId = $request->request->get('category')) {
                $category = $this->categoryRepository->find($categoryId);
                if ($category) {
                    $product->setCategory($category);
                }
            }
            
            // Handle image upload
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('products_directory'),
                    $newFilename
                );
                $product->setImage('uploads/products/'.$newFilename);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Product created successfully.');
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product): Response
    {
        if ($request->isMethod('POST')) {
            $product->setName($request->request->get('name'));
            $product->setDescription($request->request->get('description'));
            $product->setPrice((float)$request->request->get('price'));
            $product->setStock((int)$request->request->get('stock'));
            
            // Set category
            if ($categoryId = $request->request->get('category')) {
                $category = $this->categoryRepository->find($categoryId);
                if ($category) {
                    $product->setCategory($category);
                }
            }
            
            // Handle image upload
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('products_directory'),
                    $newFilename
                );
                $product->setImage('uploads/products/'.$newFilename);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Product updated successfully.');
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Product $product): Response
    {
        // Remove the product image if it exists
        if ($product->getImage()) {
            $imagePath = $this->getParameter('kernel.project_dir').'/public/'.$product->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('success', 'Product deleted successfully.');
        return $this->redirectToRoute('admin_products_index');
    }
} 