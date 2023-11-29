<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist')]
    public function index(): Response
    {
        return $this->render('artist/index.html.twig', [
            'controller_name' => 'ArtistController',
        ]);
    }


    #[Route('/artist/{id}', name: 'show_artist')]
    public function show(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager): Response {
    $user = $security->getUser();

        return $this->render('artist/show.html.twig', [
            'user' => $user,

        ]);
    }
}
