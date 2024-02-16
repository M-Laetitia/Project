<?php

namespace App\Controller;

use App\Repository\AreaRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(AreaRepository $areaRepository,  WorkshopRepository $workshopRepository): Response
    {

        $formattedEvents = []; // formater les events pour les rendre compatibles avec FullCalendar

        // ^ events 
        $ongoingEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['ARCHIVED'],
        ]);

        foreach ($ongoingEvents as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getName(),
                'start' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d H:i:s'),
                'slug' => $event->getSlug(),
            ];
        }

        // ^ expositions
        $ongoingExpo = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastExpo = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['ARCHIVED'],
        ]);

        foreach ($ongoingExpo as $expo) {
            $formattedEvents[] = [
                'id' => $expo->getId(),
                'title' => $expo->getName(),
                'start' => $expo->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $expo->getEndDate()->format('Y-m-d H:i:s'),
                'slug' => $expo->getSlug(),
            ];
        }

        // ^ workshop
        $ongoingWorkshop = $workshopRepository->findBy([
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastWorkshop = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ]);

        foreach ($ongoingWorkshop as $workshop) {
            $formattedEvents[] = [
                'id' => $workshop->getId(),
                'title' => $workshop->getName(),
                'start' => $workshop->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $workshop->getEndDate()->format('Y-m-d H:i:s'),
                'slug' => $workshop->getSlug(),
            ];
        }

        // dd($formattedEvents);
        
        return $this->render('calendar/index.html.twig', [
            'formattedEvents' => json_encode($formattedEvents),

        ]);
    }
}
