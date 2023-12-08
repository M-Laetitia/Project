<?php

namespace App\Controller;


use App\Entity\Area;
use App\Form\EventType;
use App\Repository\AreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(AreaRepository $areaRepository): Response
    {

        $events = $areaRepository->findBy(['type' => 'EVENT']);
        return $this->render('event/index.html.twig', [
            // 'controller_name' => 'DashboardController',
            'events' => $events,
        ]);
    }

    // on nomme l'id id pour utiliser le paramConverter - faire le lien avec l'object qu'on souhaite facilement
    #[Route('/event/{id}', name: 'show_event')]
    public function show(Area $area = null): Response
    {

        return $this->render('event/show.html.twig', [
            'area' => $area,
        ]);
    }

    #[Route('/dashboard/new', name:'new_event')]
    #[Route('/dashboard/{id}/edit', name:'edit_event')]
    public function new_edit(Area $area = null, Request $request, EntityManagerInterface $entityManager ) : Response
    {

        if(!$area) {
            $area = new Area();
        }

        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $event = $form->getData();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

    
        return $this->render('dashboard/newEvent.html.twig', [
            'formAddEvent' => $form,
            'edit' =>$event->getId(),
        ]);
    }
}
