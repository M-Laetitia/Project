<?php

namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        // if (!$event) {
        //     return $this->redirectToRoute('app_event');
        // }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    // #[Route('/dashboard/new', name:'new_event')]
    // #[Route('/dashboard/{id}/edit', name:'edit_event')]
    // public function new_edit(Event $event = null, Request $request, EntityManagerInterface $entityManager ) : Response
    // {

    //     if(!$event) {
    //         $event = new Event();
    //     }

    //     $form= $this->createForm(EventType::class, $event);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid() ) {
    //         $event = $form->getData();
    //         $entityManager->persist($event);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_dashboard');
    //     }

    
    //     return $this->render('dashboard/newEvent.html.twig', [
    //         'formAddEvent' => $form,
    //         'edit' =>$event->getId(),
    //     ]);
    // }
}
