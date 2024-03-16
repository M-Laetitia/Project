<?php

namespace App\Controller;

use App\Entity\Area;
use App\Form\SearchCalendarType;
use App\Repository\AreaRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(AreaRepository $areaRepository, WorkshopRepository $workshopRepository, Request $request, Security $security, ValidatorInterface $validator): Response
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
        $resultsByKeywords = [];
        $resultsByStatus = [];
        $resultsByPeriod = [];
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
                'resultsByKeywords' => $resultsByKeywords,
                'resultsByStatus' => $resultsByStatus, 
                'resultsByPeriod' => $resultsByPeriod,
            ]);
        

        }

        // ^ Search (keyword)
        
        if ($request->query->has('formSearchKeyword')) {

            // $keyword = $request->query->get('keyword'); 

             // Échapper les données soumises
             $keyword = htmlspecialchars($request->query->get('keyword'), ENT_QUOTES, 'UTF-8');
             $errors = $validator->validate($keyword, new Length(['min' => 4]));

             if (count($errors) > 0) {
                // gerer l'erreur
            } else {
                $resultsForArea = $areaRepository->searchByKeyword($keyword);
                $resultsForWorkshop = $workshopRepository->searchByKeyword($keyword);
    
                // Fusionner les résultats des deux recherches
                $resultsByKeywords = array_merge($resultsForArea, $resultsForWorkshop);
    
                $noResultsFound = empty($results);
    
                return $this->render('calendar/index.html.twig', [
                    'formattedEvents' => json_encode($formattedEvents),
                    'results' => $results, 
                    'noResultsFound' => $noResultsFound,
                    'resultsByKeywords' => $resultsByKeywords,
                    'resultsByStatus' => $resultsByStatus, 
                    'resultsByPeriod' => $resultsByPeriod,
        
                ]);
            }

           
        }

        // ^ Search (status)       

        if ($request->query->has('formSearchStatus')) {
           
            // $status = $request->query->get('status'); 
            $status = htmlspecialchars($request->query->get('status'), ENT_QUOTES, 'UTF-8');
        //   dd($status);
            // Validation des données : Initialiser une liste pour autoriser uniquement les valeurs attendues
            $allowedStatus = ['open', 'closed', 'pending'];
            
            if (!in_array($status, $allowedStatus)) {
                // Gérer le cas où la type n'est pas autorisée (>error 404);
                throw $this->createNotFoundException('Invalid type');
            }

            switch ($status) {
                case 'open':
                    $areasOpen = $areaRepository->findBy([
                        'status' => ['OPEN'],
                    ]);

                    ;
                    $workshopOpen  = $workshopRepository->findBy([
                        'status' => ['OPEN'],
                    ]);

                    $resultsByStatus = array_merge($areasOpen, $workshopOpen);

                    break;

                case 'closed':
                    $areasClosed = $areaRepository->findBy([
                        'status' => ['CLOSED'],
                    ]);
                    $workshopClosed =  $workshopRepository->findBy([
                        'status' => ['CLOSED'],
                    ]);

                    $resultsByStatus = array_merge($areasClosed, $workshopClosed);

                    break;

                case 'pending':

                    $areasPending = $areaRepository->findBy([
                        'status' => ['PENDING'],
                    ]);
                    $workshopPending =  $workshopRepository->findBy([
                        'status' => ['PENDING'],
                    ]);

                    $resultsByStatus = array_merge($areasPending, $resultsByStatus);

                    break;
                default:
                    // 
                    break;
            }
        
            // Vérifier si aucun résultat n'a été trouvé
            $noResultsFound = empty($results);
            // dd($resultsByStatus);
            return $this->render('calendar/index.html.twig', [
                'formattedEvents' => json_encode($formattedEvents),
                'results' => $results,
                'noResultsFound' => $noResultsFound,
                'resultsByKeywords' => $resultsByKeywords,
                'resultsByStatus' => $resultsByStatus, 
                'resultsByPeriod' => $resultsByPeriod,
            ]);
        

        }

        // ^ Search (period)
        if ($request->query->has('formSearchPeriod')) { 
            $period = $request->query->get('period'); 
            

            $allowedPeriod = ['week', 'days', 'months'];
            
            if (!in_array($period, $allowedPeriod)) {
                // Gérer le cas où la type n'est pas autorisée (>error 404);
                throw $this->createNotFoundException('Invalid type');
            }

            switch ($period) {
                case 'week':
                    $areasPeriod = $areaRepository->searchByPeriod($period);
                    $workshopPeriod = $workshopRepository->searchByPeriod($period);
                    $resultsByPeriod = array_merge($areasPeriod, $workshopPeriod);
                    break;

                case 'days':
                    $areasPeriod = $areaRepository->searchByPeriod($period);
                    $workshopPeriod = $workshopRepository->searchByPeriod($period);
                    $resultsByPeriod = array_merge($areasPeriod, $workshopPeriod);
                    
                    break;

                case 'months':
                    $areasPeriod = $areaRepository->searchByPeriod($period);
                    $workshopPeriod = $workshopRepository->searchByPeriod($period);
                    $resultsByPeriod = array_merge($areasPeriod, $workshopPeriod);
                    break;

                default:
                    // 
                    break;
            }
            // dd($resultsByPeriod);
            // Vérifier si aucun résultat n'a été trouvé
            $noResultsFound = empty($results);
            // dd($resultsByStatus);
            return $this->render('calendar/index.html.twig', [
                'formattedEvents' => json_encode($formattedEvents),
                'results' => $results,
                'noResultsFound' => $noResultsFound,
                'resultsByKeywords' => $resultsByKeywords,
                'resultsByStatus' => $resultsByStatus, 
                'resultsByPeriod' => $resultsByPeriod,
            ]);


        }


        // ^ Reset
        if ($request->query->has('reset')) {
            return $this->render('calendar/index.html.twig', [
                'formattedEvents' => json_encode($formattedEvents),
                'results' => $results, 
                'noResultsFound' => $noResultsFound,
                'resultsByKeywords' => $resultsByKeywords,
    
            ]);
        }

       
        return $this->render('calendar/index.html.twig', [
            'formattedEvents' => json_encode($formattedEvents),
            'results' => $results, 
            'noResultsFound' => $noResultsFound,
            'resultsByKeywords' => $resultsByKeywords,
            'resultsByStatus' => $resultsByStatus, 
            'resultsByPeriod' => $resultsByPeriod,

        ]);
    }

    #[Route('/archives', name: 'app_archives')]
    public function indexArchives (Request $request, AreaRepository $areaRepository, WorkshopRepository $workshopRepository) : Response
    {

        $pastAreas = $areaRepository->findBy([
            // 'type' => 'EVENT',
            'status' => ['ARCHIVED'],
        ]);
        $pastWorkshops = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ]);

        // merge events, expos and workshops
        $allPastEvents = array_merge($pastAreas, $pastWorkshops);

        // usort($array, $callback_function);
        // An anonymous function using the syntax function($a, $b).
        // This expression uses the spaceship comparison operator (<=>) to compare the start dates (startDate) of the two events $a and $b.
        usort($allPastEvents, function($a, $b) {
            return $a->getStartDate() <=> $b->getStartDate();
        });

        //Extracts the first five elements from the array.
        $latestEvents = array_slice($allPastEvents, 0, 5);

        return $this->render('calendar/archives.html.twig', [
             'latestEvents' => $latestEvents,
        ]);
    }
}
