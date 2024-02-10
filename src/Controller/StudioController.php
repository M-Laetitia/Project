<?php

namespace App\Controller;

use App\Entity\Studio;
use App\Entity\Timeslot;
use App\Form\TimeSlotType;
use App\Service\MailerService;
use App\Entity\WorkshopRegistration;
use App\Repository\StudioRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WorkshopRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudioController extends AbstractController
{
    // ^ list art studios (user)
    #[Route('/studio', name: 'app_studio')]
    public function index(StudioRepository $studioRepository): Response
    {
        $studios = $studioRepository->findBy([]);

        return $this->render('studio/index.html.twig', [
            'studios' => $studios,

        ]);
    }

    // ! pb route à régler !!! conflit avec studio/dashboard'
    // ^ show art studio (user)
    #[Route('/studio/show/{slug}', name: 'show_studio')]
    public function show(Studio $studio =null, StudioRepository $studioRepository, WorkshopRegistrationRepository $workshopRegistrationRepository, Security $security): Response
    {
  
        $studioTimeslots = $studio->getTimeslots();
        $user = $security->getUser();
        
        $timeslotRegistrations = [];
        foreach ($studioTimeslots as $timeslot) {
            $timeslotId = $timeslot->getId();
            $nbRegistrationPerTimeslot = $workshopRegistrationRepository->getRegistrationPerTimeslot($timeslotId);
            
            // dump($nbRegistrationPerTimeslot);die;

            // Stockez le nombre de réservations par timeslot dans un tableau
            $timeslotRegistrations[$timeslotId] = $nbRegistrationPerTimeslot;
            // dump($timeslotRegistrations[$timeslotId]);die;
        }

        // dump($timeslotId);die;
        return $this->render('studio/show.html.twig', [
            'studio' => $studio,
            'studioTimeslots' => $studioTimeslots,
            'timeslotRegistrations' => $timeslotRegistrations,
            'user' => $user,
            // 'nbRegistrationPerTimeslot' => $nbRegistrationPerTimeslot,

        ]);
    }

    // ^show art studio (admin)
    #[Route('/dashboard/{slug}/studio', name: 'show_studio_admin')]
    public function show_adminStudio(Studio $studio): Response 
    {
        return $this->render('dashboard/showStudio.html.twig', [
            'studio' => $studio, 
        ]);
    }

    // ^ list art studios / planning (supervisor + admin)
    #[Route('/studio/dashboard', name: 'studio_dashboard')]
    public function index_dashboard(StudioRepository $studioRepository, TimeslotRepository $timeslotRepository, Security $security): Response
    {

        $user = $security->getUser();
        $studios = $studioRepository->findBy([]);
        foreach ($studios as $studio) {
            $studioId = $studio->getId();
        }

        $timeslots = $timeslotRepository->findBy([]);

        $formattedTimeslots = []; // formater les events pour les rendre compatibles avec FullCalendar

        foreach ($timeslots as $timeslot) {
            $enlistedUsers = [];
            foreach ($timeslot->getWorkshopRegistrations() as $registration) {
                $fullName = $registration->getFirstname() . ' ' . $registration->getLastname();
                $enlistedUsers[] = $fullName;
            }

            $formattedTimeslots[] = [
                'id' => $timeslot->getId(),
                'start' => $timeslot->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $timeslot->getEndDate()->format('Y-m-d H:i:s'),
                'studio' => $timeslot->getStudio()->getName(),
                'supervisor' => $timeslot->getUser()->getUsername(),
                'enlisted' => $timeslot->getNbRegistrations(),
                'capacity' => $timeslot->getStudio()->getNbRooms(),
                'enlistedUsers' => $enlistedUsers,
            ];
           
        }

        return $this->render('studio/supervisorDashboard.html.twig', [
            'studios' => $studios,
            'timeslots' => $timeslots, 
            // 'timeslotsPerStudio' => $timeslotsPerStudio,
            'formattedTimeslots' => json_encode($formattedTimeslots), // Passer les données formatées en JSON à la vue
            'user' => $user,

        ]);
    }

    // ^ Create timeslot (supervisor)
    #[Route('/studio/{id}/dashboard', name: 'new_timeslot')]
    public function new(Timeslot $timeslot = null, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {

        $user = $security->getUser();

        if(!$timeslot) {
            $timeslot = new Timeslot();
        }

        $form = $this->createForm(TimeSlotType::class, $timeslot );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) 
        {

            // Récupérer les données soumises par le formulaire
            $startTime = $form ->get('TimeSlotAvailability')->getData()->getStartTime();
            $endTime = $form ->get('TimeSlotAvailability')->getData()->getEndTime();

            $day = $form->get('day')->getData();
            $dayString = $day->format('Y-m-d');

            // Combiner la date sélectionnée avec les heures de début et de fin pour obtenir les DateTime complets
            $startDateString = $dayString . ' ' . $startTime->format('H:i:s');
            $endDateString = $dayString . ' ' . $endTime->format('H:i:s');

            // Convertir les chaînes de caractères en objets DateTime complets
            $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $startDateString);
            $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $endDateString);

            $timeslot->setUser($user);
            $timeslot->setStartDate($startDate);
            $timeslot->setEndDate($endDate);

            $timeslot = $form->getData();
            $entityManager->persist($timeslot);
            $entityManager->flush();

            return $this->redirectToRoute('studio_dashboard');
        }


        return $this->render('studio/newTimeSlot.html.twig', [
            'formAddTimeslot' => $form,

        ]);
    }

     // ^ planning art studio (supervisor only)
     #[Route('/studio/{id}/planning', name: 'show_planning')]
     #[isGranted("ROLE_SUPERVISOR")]
     public function index_planning(StudioRepository $studioRepository, TimeslotRepository $timeslotRepository, Security $security): Response
     {
 

        $user = $security->getUser();

        if(!$user) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
            // return $this->redirectToRoute('app_home');
        }
    

        $studios = $studioRepository->findBy([]);
         // dump($studios);die;
        $timeslots = $timeslotRepository->findBy(['user' => $user]);



        return $this->render('studio/showPlanning.html.twig', [
            'user' => $user,
            'studios' => $studios,
            'timeslots' => $timeslots, 
            
 
         ]);
     }

    // ^ delete timeslot (supervisor)
    #[Route('/studio/{id}/planning/delete', name: 'delete_timeslot')]
    #[isGranted("ROLE_SUPERVISOR")]
    public function delete_timeslot(Timeslot $timeslot, EntityManagerInterface $entityManager, Security $security) : Response
    {

        $user = $security->getUser();
        $entityManager->remove($timeslot);
        $entityManager->flush();

        return $this->redirectToRoute('show_planning', ['id' => $user->getId()]);
    }

    // ! throw?
    // ^ Delete registration for timeslot - studio (admin)
    #[Route('/studio/{id}/registration/delete', name: 'delete_timeslot_registration')]
    #[isGranted("ROLE_SUPERVISOR")]
    public function delete_timeslot_registration(WorkshopRegistration $workshopRegistration, EntityManagerInterface $entityManager, Security $security) : Response
    {

        $studioId = $workshopRegistration->getTimeslot()->getStudio()->getId();

        // Vérifier si le studio existe
        // if (!$studio) {
        //     throw $this->createNotFoundException('Studio not found.');
        // }

        // $studioId = $studio->getId();
        $user = $security->getUser();
        $entityManager->remove($workshopRegistration);
        $entityManager->flush();

        return $this->redirectToRoute('show_studio_admin', ['id' => $studioId]);
    }

 
   
}
