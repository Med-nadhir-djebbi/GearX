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
        if(!$security->isGranted('ROLE_ADMIN') || !$security->getUser()){
            return $this->render('errors/not-found.html.twig', ['message' => 'Page not found.']);
        }
        return $this->redirectToRoute('home');
    }

}