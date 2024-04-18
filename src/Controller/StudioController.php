<?php

namespace App\Controller;

use App\Entity\Studio;
use App\Entity\Timeslot;
use App\Form\StudioType;
use App\Form\TimeSlotType;
use App\Form\PictureFormType;
use App\Service\MailerService;
use App\Service\PictureService;
use App\Entity\WorkshopRegistration;
use App\Repository\StudioRepository;
use App\Repository\PictureRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TimeSlotAvailabilityRepository;
use App\Repository\WorkshopRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudioController extends AbstractController
{
    // ^ list art studios (user)
    #[Route('/studio', name: 'app_studio')]
    public function index(StudioRepository $studioRepository,TimeslotRepository $timeslotRepository, Security $security): Response
    {
        $user = $security->getUser();
        $studios = $studioRepository->findBy([]);
        $timeslots = $timeslotRepository->findBy([]);
        $formattedTimeslots = [];

        foreach ($timeslots as $timeslot) {
            $enlistedUsers = [];
            foreach ($timeslot->getWorkshopRegistrations() as $registration) {
                $fullName = $registration->getFirstname() . ' ' . $registration->getLastname();
                $enlistedUsers[] = $fullName;
            }

            $formattedTimeslots[] = [
                'idTimeslot' => $timeslot->getId(),
                'start' => $timeslot->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $timeslot->getEndDate()->format('Y-m-d H:i:s'),
                'studio' => $timeslot->getStudio()->getName(),
                'supervisor' => $timeslot->getUser()->getUsername(),
                'enlisted' => $timeslot->getNbRegistrations(),
                'capacity' => $timeslot->getStudio()->getNbRooms(),
                'enlistedUsers' => $enlistedUsers,
                
            ];
           
        }

        return $this->render('studio/index.html.twig', [
            'studios' => $studios,
            'formattedTimeslots' => json_encode($formattedTimeslots), 
            'user' => $user,

        ]);
    }


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

            // Stockez le nombre de réservations par timeslot dans un tableau
        }

        return $this->render('studio/show.html.twig', [
            'studio' => $studio,
            'studioTimeslots' => $studioTimeslots,
            'timeslotRegistrations' => $timeslotRegistrations,
            'user' => $user,
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

    // ^ Create timeslot (supervisor)
    #[Route('/supervisor/studio/dashboard/{studioId}/{selectedDate}', name: 'new_timeslot')]
    #[IsGranted("ROLE_SUPERVISOR")]
    public function new(Timeslot $timeslot = null, Request $request, $studioId, $selectedDate, TimeslotRepository $tsr, StudioRepository $studioRepository, EntityManagerInterface $entityManager, TimeSlotAvailabilityRepository $timeslotRepository, Security $security): Response
    {

        $user = $security->getUser();
        $studios = $studioRepository->findBy([]);
        // foreach ($studios as $studio) {
        //     $studioId = $studio->getId();
        // }

        $timeslotAvalaibles = $timeslotRepository->findAvailableTimeSlots($studioId, $selectedDate);

        if(!$timeslot) {
            $timeslot = new Timeslot();
        }
        // Récupérer les paramètres de l'URL
        // $studioID = $request->attributes->get('studioId');    
        $studio = $studioRepository->find($studioId);
        

        $form = $this->createForm(TimeSlotType::class, null, [
            'studioId' => $request->attributes->get('studioId'),
            'selectedDate' => $request->attributes->get('selectedDate'),
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) 
        {
            $day = $selectedDate;
            $dayDate = \DateTime::createFromFormat('Y-m-d', $day);

            $selectedTimeSlotAvailability = $form->get('TimeSlotAvailability')->getData();
            $selectedTimeSlotAvailabilityId = $selectedTimeSlotAvailability->getId();
            $timeslotSelected= $timeslotRepository->find($selectedTimeSlotAvailabilityId);

            $selectedTimeSlotAvailability = $form->get('TimeSlotAvailability')->getData();
            // Extraire l'heure de début et de fin de l'objet TimeSlotAvailability
            $startDate = $selectedTimeSlotAvailability->getStartTime();
            $endDate = $selectedTimeSlotAvailability->getEndTime();
            
            // Extraire l'heure sous forme de chaîne de caractères
            $startTime = $startDate->format('H:i:s');
            $endTime = $endDate->format('H:i:s');
            
            // Combiner la date sélectionnée avec les heures de début et de fin pour obtenir les DateTime complets
            $startDateString = $day . ' ' . $startTime;
            $endDateString = $day . ' ' . $endTime;
            

            // Convertir les chaînes de caractères en objets DateTime complets
            $finalStartDate = \DateTime::createFromFormat('Y-m-d H:i:s', $startDateString);
            $finalEndDate = \DateTime::createFromFormat('Y-m-d H:i:s', $endDateString);
            
            // var_dump($finalStartDate, $finalEndDate);die;
            // dd($timeslotSelected);

            // dd($studioId, $dayDate, $selectedTimeSlotAvailabilityId );

            // check if a timeslot already exists for this date and studio 
            $existingTimeslot = $tsr->findBy([
                'studio' => $studioId,
                'date' => $dayDate,  
                'timeSlotAvailability' => $selectedTimeSlotAvailabilityId,
            ]);
            if ($existingTimeslot) {
                $this->addFlash('error', 'This time slot is already booked. Please choose another time slot.');
                return $this->redirectToRoute('studio_dashboard');
            }

            $timeslot->setUser($user);
            $timeslot->setDate($dayDate);
            $timeslot->setStudio($studio);
            $timeslot->setStartDate($finalStartDate);
            $timeslot->setEndDate($finalEndDate);
            $timeslot->setTimeSlotAvailability($timeslotSelected);
            
            // $timeslot = $form->getData();

            $entityManager->persist($timeslot);
            $entityManager->flush();

            return $this->redirectToRoute('studio_dashboard');
        }


        return $this->render('studio/newTimeSlot.html.twig', [
            'formAddTimeslot' => $form,
            // 'studios' => $studios,
            'user' => $user,
            // 'findAvailableTimeSlots' => $findAvailableTimeSlots,
            'timeslotAvalaibles' => $timeslotAvalaibles,

        ]);
    }

    // ^ list art studios / planning (supervisor)
    #[Route('/supervisor/studio/dashboard', name: 'studio_dashboard')]
    #[IsGranted("ROLE_SUPERVISOR")]
    public function index_dashboard(StudioRepository $studioRepository, TimeslotRepository $timeslotRepository, Security $security): Response
    {

        $user = $security->getUser();
        $studios = $studioRepository->findBy([]);
        $timeslots = $timeslotRepository->findBy([]);
        $ongoingTimeslots = $timeslotRepository->findOngoingTimeslots();

        $studiosWithTimeslots = $studioRepository->findOngoingTimeslotsPerStudio();


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
            'ongoingTimeslots' => $ongoingTimeslots,
            'studiosWithTimeslots' => $studiosWithTimeslots,
            // 'timeslotsPerStudio' => $timeslotsPerStudio,
            'formattedTimeslots' => json_encode($formattedTimeslots), // Passer les données formatées en JSON à la vue
            'user' => $user,

        ]);
    }



    
     // ^ planning art studio (supervisor only)
     #[Route('/supervisor/studio/{id}/planning', name: 'show_planning')]
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
    #[Route('/supervisor/studio/{id}/planning/delete', name: 'delete_timeslot')]
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
    #[Route('/supervisor/studio/{id}/registration/delete', name: 'delete_timeslot_registration')]
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

    // ^ new/edit event (admin)
    #[Route('/dashboard/studio/new', name:'new_studio', priority:1)]
    #[Route('/dashboard/studio/{slug}/edit', name:'edit_studio')]
    #[IsGranted("ROLE_ADMIN")]
    public function new_studio(Studio $studio = null, Request $request, PictureRepository $pictureRepo, PictureService $pictureService, EntityManagerInterface $entityManager ) : Response
    {
        $isNewStudio = !$studio;

        if(!$studio) {
            $studio = new Studio();
        }

        $maxImagesAllowed = 12;
        $studioId = $studio->getId();
        $numberOfImages = count($pictureRepo->findBy(['studio' => $studioId, 'type' => 'picture']));
        $canUploadImage = $numberOfImages < $maxImagesAllowed;


        $bannerExists = null;
        $existingBanner = $pictureRepo->findOneBy(['studio' => $studioId, 'type' => 'banner']); 
        if ($existingBanner) {
            $bannerExists =  $existingBanner->getPath();
        }

        $previewExists = null;
        $existingPreview = $pictureRepo->findOneBy(['studio' => $studioId, 'type' => 'preview']); 
        if ($existingPreview) {
            $previewExists =  $existingPreview->getPath();
        }

        $form= $this->createForm(StudioType::class, $studio);
        $form->handleRequest($request);

        $formPicture = $this->createForm(PictureFormType::class);      
        $formPicture->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() ) {

            // Retrieve the data entered in the text field from the request
            $inputEquipment =  $form->get('equipment')->getData();
            // Split the data using comma as delimiter to obtain a list of words
            $words = explode(',', $inputEquipment);
            // Remove spaces before and after each word
            $words = array_map('trim', $words);

            
            $studio = $form->getData();
            $studio->setSlug($studio->generateSlug());
            $studio->setEquipment($words);

            $entityManager->persist($studio);
            $entityManager->flush();

            $message = $isNewStudio ? 'Studio created successfully!' : 'Studio edited successfully!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('edit_studio', ['slug' => $studio->getSlug()]);

        }

         // ^ GALLERY IMAGES
         $picturesGallery = $pictureRepo->findBy(['area' => $studioId, 'type' => 'picture']); 

         $folder = $studio->getName();
         
         
         if ($formPicture->isSubmitted() && $formPicture->isValid() && $numberOfImages < $maxImagesAllowed ) {
             $pictureFile = $formPicture->get('picture')->getData();
              // on appelle le service d'ajout
             if ($pictureFile !== null) 
             {
                 $file = $pictureService->add($pictureFile, $folder, 500, 500);
                 $img = new Picture();
                 $img = $formPicture->getData();
                 $img->setPath($file);
                 $img->setType('picture');
                 $img->setStudio($studio);
                 $entityManager->persist($img);
                 $entityManager->flush();   
                 
                 $this->addFlash('success', 'Your picture has been successfully added');
                 return $this->redirectToRoute('edit_studio', ['slug' => $studio->getSlug()]);
             }           
         

           } else {
               // $this->addFlash('error', 'Maximum image limit reached. Please delete some before adding more.');
               // return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
           }

        return $this->render('dashboard/newStudio.html.twig', [
            'studio' =>$studio,
            'edit' =>$studio->getId(),
            'formAddStudio' => $form,

            'maxImagesAllowed' => $maxImagesAllowed,
            'canUploadImage' => $canUploadImage,
            'formAddPictureGallery' => $formPicture,
            'picturesGallery' => $picturesGallery,

            'bannerExists' => $bannerExists,
            'previewExists' => $previewExists,
        ]);
    }

 
   
}
