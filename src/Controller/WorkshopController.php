<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkshopController extends AbstractController
{
    #[Route('/workshop', name: 'app_workshop')]
    public function index(WorkshopRepository $workshopRepository): Response
    {

        $workshops = $workshopRepository->findBy([]);

        return $this->render('workshop/index.html.twig', [
            'workshops' => $workshops,
        ]);
       
    }

    #[Route('/workshop/{id}', name: 'show_workshop')]
    public function show(Workshop $workshop = null): Response
    {

        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }

}
