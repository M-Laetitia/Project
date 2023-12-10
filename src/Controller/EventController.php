<?php

namespace App\Controller;


use App\Entity\Area;
use App\Form\EventType;
use App\Repository\AreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AreaParticipationRepository;
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

    // ^ show event (admin)
    #[Route('/dashboard/{id}', name: 'show_event_admin')]
    public function show_admin(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
   
        return $this->render('dashboard/showEvent.html.twig', [
            'area' => $area,

        ]);
    }


    // ^ show event (user)
    // on nomme l'id id pour utiliser le paramConverter - faire le lien avec l'object qu'on souhaite facilement
    #[Route('/event/{id}', name: 'show_event')]
    public function show(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
        $user = $security->getUser();
        $userId = $user->getId();
        $areaId = $area->getId();

        $existingParticipation = [];

        $hasExistingParticipation = $areaParticipationRepository->findOneBy(['user' => $user->getId(), 'area' => $areaId]);
        $existingParticipation = $hasExistingParticipation !== null;

        return $this->render('event/show.html.twig', [
            'area' => $area,
            'existingParticipation' => $existingParticipation
        ]);
    }



    #[Route('/dashboard/new/event', name:'new_event')]
    #[Route('/dashboard/{id}/edit/event', name:'edit_event')]
    public function new_edit(Area $area = null, Request $request, EntityManagerInterface $entityManager ) : Response
    {

        if(!$area) {
            $area = new Area();
        }

        $form= $this->createForm(EventType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $area->setType('EVENT');
            $area = $form->getData();
            $entityManager->persist($area);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

    
        return $this->render('dashboard/newEvent.html.twig', [
            'formAddEvent' => $form,
            'edit' =>$area->getId(),
        ]);
    }

    // ^ Delete Event (admin)

    #[Route('/dashboard/{id}/delete/event', name: 'delete_event')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete_event(Area $area, EntityManagerInterface $entityManager) :Response 
    {
        $entityManager->remove($area);
        $entityManager->flush();
        
    
        

        return $this->redirectToRoute('app_dashboard');
    }
}
