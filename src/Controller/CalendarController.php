<?php

namespace App\Controller;

use App\Entity\Area;
use App\Form\SearchCalendarType;
use App\Repository\AreaRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(AreaRepository $areaRepository, WorkshopRepository $workshopRepository, Request $request): Response
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

        // ^ Search (type)
        $results = []; // Initialisation du tableau des résultats
        $noResultsFound = false;
        // dd($discipline);
        if ($request->query->has('type')) {
            $type = $request->query->get('type'); 
           

            // Validation des données : Initialiser une liste pour autoriser uniquement les valeurs attendues
            $allowedTypes = ['event', 'expo', 'workshop'];
            
            if (!in_array($type, $allowedTypes)) {
                // Gérer le cas où la type n'est pas autorisée (>error 404);
                throw $this->createNotFoundException('Invalid type');
            }

            switch ($type) {
                case 'event':
                    $results = $ongoingEvents;
                    break;
                case 'expo':
                    $results = $ongoingExpo;
                    break;
                case 'workshop':
                    $results = $ongoingWorkshop;
                    break;
                default:
                    // 
                    break;
            }
        
            // Vérifier si aucun résultat n'a été trouvé
            $noResultsFound = empty($results);
            
            return $this->render('calendar/index.html.twig', [
                'formattedEvents' => json_encode($formattedEvents),
                'results' => $results,
                'noResultsFound' => $noResultsFound,
            ]);
        

        }

        // ^ Reset
        if ($request->query->has('reset')) {
            return $this->render('calendar/index.html.twig', [
                'formattedEvents' => json_encode($formattedEvents),
                'results' => $results, 
                'noResultsFound' => $noResultsFound,
    
            ]);
        }
       
        return $this->render('calendar/index.html.twig', [
            'formattedEvents' => json_encode($formattedEvents),
            'results' => $results, 
            'noResultsFound' => $noResultsFound,

        ]);
    }
}
