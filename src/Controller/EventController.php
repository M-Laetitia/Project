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
    // ^ show list events (all)
    #[Route('/event', name: 'app_event')]
    public function index(AreaRepository $areaRepository): Response
    {
        $ongoingEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['ARCHIVED'],
        ]);



        $formattedEvents = []; // formater les events pour les rendre compatibles avec FullCalendar

        foreach ($ongoingEvents as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getName(),
                'start' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d H:i:s'),
                'slug' => $event->getSlug(),
            ];
        }

        // $response = new JsonResponse($formattedEvents);
        // dump($formattedEvents);die;

        return $this->render('event/index.html.twig', [
            'ongoingEvents' => $ongoingEvents,
            'pastEvents' => $pastEvents,
            'formattedEvents' => json_encode($formattedEvents), // Passer les données formatées en JSON à la vue
         
        ]);
    }

    // ^ Get events for calendar
    public function getEventsCalendar(AreaRepository $areaRepository): Response
    {
        $events = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);

        $formattedEvents = []; // formater les events pour les rendre compatibles avec FullCalendar

        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'start' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d H:i:s'),
            ];
        }
        

        $jsonResponse = new JsonResponse($formattedEvents);
        dump($jsonResponse); die; 

        // Pour déboguer, vous pouvez envoyer le contenu de la réponse directement dans le corps de la réponse HTTP
        // Cela peut être consulté dans l'onglet "Réseau" des outils de développement de votre navigateur
        // $jsonResponse->setContent(json_encode($formattedEvents));

        return $jsonResponse;
    }

    // ^ show event (admin)
    #[Route('/dashboard/event/{slug}', name: 'show_event_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function show_admin(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
   
        return $this->render('dashboard/showEvent.html.twig', [
            'area' => $area,
        ]);
    }

    // ^ show event (user)
    // on nomme l'id id pour utiliser le paramConverter - faire le lien avec l'object qu'on souhaite facilement
    #[Route('/event/{slug}', name: 'show_event')]
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

    // ^ new:edit event (admin)
    #[Route('/dashboard/new/event', name:'new_event')]
    #[Route('/dashboard/{id}/edit/event', name:'edit_event')]
    #[IsGranted("ROLE_ADMIN")]
    public function new_edit(Area $area = null, Request $request, EntityManagerInterface $entityManager ) : Response
    {
        $isNewEvent = !$area;

        if(!$area) {
            $area = new Area();
        }

        $form= $this->createForm(EventType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $area->setType('EVENT');
            $area = $form->getData();
            $area->setSlug($area->generateSlug());
            $entityManager->persist($area);
            $entityManager->flush();

            $message = $isNewEvent ? 'Event created successfully!' : 'Event edited successfully!';
            $this->addFlash('success', $message);
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
        
        $this->addFlash('success', 'The event has been successfully deleted');
        return $this->redirectToRoute('app_dashboard');
    }
}
