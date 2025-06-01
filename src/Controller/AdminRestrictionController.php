<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRestrictionController extends AbstractController
{
    #[Route('/not-found', name: 'custom_40')]
    public function notFound(Security $security): Response
    {
        return $this->redirectToRoute('home');
    }
    #[Route('/forbidden', name: 'custom_403')]
    public function forbidden(Security $security): Response
    {
        return $this->redirectToRoute('home');
    }
}