<?php

namespace App\Controller;

use App\Repository\AreaRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(WorkshopRepository $workshopRepository, AreaRepository $areaRepository): Response
    {

        // $events = $eventRepository->findBy([]);
        $workshops = $workshopRepository->findBy([]);
        $events = $areaRepository->findBy(['type' => 'EVENT']);
        $expositions = $areaRepository->findBy(['type' => 'EXPO']);


        return $this->render('dashboard/index.html.twig', [
            // 'controller_name' => 'DashboardController',
            'events' => $events,
            'expositions' => $expositions,
            'workshops' => $workshops
        ]);
    }
}
