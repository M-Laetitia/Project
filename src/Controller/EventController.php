<?php

namespace App\Controller;


use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(EventRepository $eventRepository): Response
    {

        $events = $eventRepository->findBy([]);

        return $this->render('event/index.html.twig', [
            // 'controller_name' => 'DashboardController',
            'events' => $events,
        ]);
    }

    // on nomme l'id id pour utiliser le paramConverter - faire le lien avec l'object qu'on souhaite facilement
    #[Route('/event/{id}', name: 'show_event')]
    public function show(Event $event = null): Response
    {

        if (!$event) {
            return $this->redirectToRoute('app_event');
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }
}
