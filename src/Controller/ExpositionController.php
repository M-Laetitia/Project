<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Form\ExpositionType;
use App\Service\MailerService;
use App\Entity\ExpositionProposal;
use App\Repository\AreaRepository;
use App\Form\AreaParticipationType;
use App\Form\ExpositionProposalType;
use App\Controller\ExpositionController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AreaParticipationRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExpositionProposalRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpositionController extends AbstractController
{
    // ^ Show list expos
    #[Route('/exposition', name: 'app_exposition')]
    public function index(AreaRepository $areaRepository, ExpositionProposalRepository $expoProposalRepository, MailerService $mailerService, Security $security): Response
    {

        if ($user = $security->getUser()){
         

            // Fetch all expo from the AreaRepository
            $expos = $areaRepository->findBy(['type' => 'EXPO']);

            $ongoingExpos = $areaRepository->findBy([
                'type' => 'EXPO',
                'status' => ['OPEN', 'PENDING', 'CLOSED'],
            ]);
            $pastExpos = $areaRepository->findBy([
                'type' => 'EXPO',
                'status' => ['ARCHIVED'],
            ]);

            //^recup nb de réservation - initialisation tableau vide
            // ! indiquer nb restant
            $reservationCounts = [];

            // Initialize arrays to store existing proposals and proposal counts
            $existingProposals = [];
            $proposalCounts = [];

            // ! vérifier si nécessaire !!
            // Iterate through each expo
            foreach ($expos as $expo) {
                // get 'id' in the Area entity
                $expositionId = $expo->getId();
                // Check if the user has an existing proposal for this exposition
                $hasExistingRequest = $expoProposalRepository->findOneBy(['user' => $user->getId(), 'area' => $expositionId]);
                $existingProposals[$expositionId] = $hasExistingRequest !== null;

                // Store the count of proposals for this exposition
                $proposalCounts[$expositionId] = $areaRepository->countProposalsPerExpo($expositionId);
            
                // Get the proposals associated with this exposition
                $expositionProposals = $expo->getExpositionProposals();

                // ^ get the reservation count
                $reservationCounts[$expositionId] = $areaRepository->countParticipationPerExpo($expositionId);

                // Initialize an array to store associated users
                $usersToNotify = [];

                // Iterate through proposals and add users to the array
                foreach ($expositionProposals as $expositionProposal) {
                    $usersToNotify[] = $expositionProposal->getUser()->getEmail();
                }

                // Check if the proposal count threshold (3 proposals) is reached
                if ($proposalCounts[$expositionId] >= 3) {
                    // Get the proposals associated with this exposition
                    $expositionProposals = $expo->getExpositionProposals();
            
                    // Initialize an array to store associated users
                    $usersToNotify = [];
            
                    // Iterate through proposals and add users to the array
                    foreach ($expositionProposals as $expositionProposal) {
                        $usersToNotify[] = $expositionProposal->getUser()->getEmail();
                    }
            
                    // Send email
                    // $mailerService->sendExpositionConfirmationEmail($expo, $usersToNotify);
                }
            }
            
            return $this->render('exposition/index.html.twig', [
                'expos' => $expos, 

                'ongoingExpos' => $ongoingExpos,
                'pastExpos' => $pastExpos,

                'existingProposals' => $existingProposals,
                'proposalCounts' => $proposalCounts,
                'reservationCounts' => $reservationCounts,
            ]);
        }


        
        // Fetch all expo from the AreaRepository
        $expos = $areaRepository->findBy(['type' => 'EXPO']);

        $ongoingExpos = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastExpos = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['ARCHIVED'],
        ]);

        //^recup nb de réservation - initialisation tableau vide
        // ! indiquer nb restant
        $reservationCounts = [];

        // Initialize arrays to store existing proposals and proposal counts
        $existingProposals = [];
        $proposalCounts = [];

        // ! vérifier si nécessaire !!
        // Iterate through each expo
        foreach ($expos as $expo) {
            // get 'id' in the Area entity
            $expositionId = $expo->getId();
            // Check if the user has an existing proposal for this exposition
            
            // Store the count of proposals for this exposition
            $proposalCounts[$expositionId] = $areaRepository->countProposalsPerExpo($expositionId);
           
            // Get the proposals associated with this exposition
            $expositionProposals = $expo->getExpositionProposals();

            // ^ get the reservation count
            $reservationCounts[$expositionId] = $areaRepository->countParticipationPerExpo($expositionId);

            // Initialize an array to store associated users
            $usersToNotify = [];

            
            // Check if the proposal count threshold (3 proposals) is reached
           
        }
        
        return $this->render('exposition/index.html.twig', [
            'expos' => $expos, 

            'ongoingExpos' => $ongoingExpos,
            'pastExpos' => $pastExpos,

        
            'proposalCounts' => $proposalCounts,
            'reservationCounts' => $reservationCounts,
        ]);
    }

    // ^ Show detail expo (user)
    #[Route('/exposition/{slug}', name: 'show_exposition' )]
    public function show(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService ): Response 
    {


        if($user = $security->getUser()) {
            $userId = $user->getId();
            $areaId = $area->getId();
            $areaSlug = $area->getSlug();
    
            $existingParticipation = [];
            // ! voir pour utiliser dql
            // $existingParticipation = $areaParticipationRepository->checkIfUserHasExistingParticipation($userId, $areaId);
    
            $hasExistingParticipation = $areaParticipationRepository->findOneBy(['user' => $user->getId(), 'area' => $areaId]);
            $existingParticipation = $hasExistingParticipation !== null;
    
            // ^ FORM
    
            $form = $this->createForm(AreaParticipationType::class);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid() ) {
    
                // Check if the maximum number of participants has been reached
                $maxParticipants = $area->getNbRooms();
                $currentParticipants = $area->getNbReversationMade();
    
                if ($currentParticipants < $maxParticipants) {
    
                    $expoParticipation = $form->getData();
                    $expoParticipation->setInscriptionDate(new \DateTimeImmutable());
                    $expoParticipation->setUser($user);
                    $expoParticipation->setArea($area);
        
                    $entityManager->persist($expoParticipation);
                    $entityManager->flush();
        
                    //send the email
                    $userEmail = $user->getEmail();
                    $expositionDetails = sprintf(
                        "Name: %s\r\nStartDate:  %s\r\nEndDate: %s\r\nDescription: %s\r\n", 
                        $area->getName(),
                        $area->getStartDate()->format('Y-m-d H:i:s'),
                        $area->getEndDate()->format('Y-m-d H:i:s'),
                        $area->getDescription()
                    );
                    $mailerService->sendExpositionParticipationConfirmation($userEmail, $expositionDetails);
        
                    // Check if the maximum number of participants has been reached after the new registration
                    $nbReversationRemaining = $area->getNbReversationRemaining();
                    
                    if ( $currentParticipants +1 >= $maxParticipants && $area->getStatus() !== 'CLOSED') {
                        // Update the status to "closed"
                        $area->setStatus('CLOSED');
                        $entityManager->flush();
                    } elseif ($currentParticipants < $maxParticipants && $area->getStatus() !== 'OPEN') {
                        $area->setStatus('OPEN');
                        $entityManager->flush();
                    }
        
                    // ! redirect sur une nouvelle page pour dire que c'est un succès, qu'un mail a été envoyé, + récup pdf
                    $this->addFlash('success', 'You have been successfully registered for this exposition. A confirmation e-mail has been sent to you.');
    
                    
                    return $this->redirectToRoute('show_exposition', ['slug' => $areaSlug]);
    
                } else {
                    // Redirect / display a message indicating that the maximum number of participants has been reached
                    // return $this->render('exposition/maxParticipantsReached.html.twig');
                    //! redirection ver detail expo ou liste expo?
                    return $this->redirectToRoute('show_exposition', ['slug' => $areaSlug]);
                    // ('show_event_admin', ['id' => $areaId]);
                }
    
            
            }
    

            return $this->render('exposition/show.html.twig', [
                'area' => $area,
                'existingParticipation' => $existingParticipation,
                'formSendParticipation' => $form,
                'user' => $user,
            ]);
        }
        
        
        $areaSlug = $area->getSlug();

        return $this->render('exposition/show.html.twig', [
            'area' => $area,
        ]);
    }


    // ^create new expo (admin)
    #[Route('/dashboard/expo/new', name:'new_expo' , priority:1)]
    #[Route('/dashboard/expo/{slug}/edit', name:'edit_expo')]
    #[IsGranted("ROLE_ADMIN")]
    public function new_edit_Expo(Area $area = null, Request $request, EntityManagerInterface $entityManager ) : Response
    {

    
        $isNewEvent = !$area;

        
        if(!$area) {
            $area = new Area();
        }

        $form= $this->createForm(ExpositionType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $area->setType('EXPO');
            $area = $form->getData();
            $entityManager->persist($area);
            $area->setSlug($area->generateSlug());
            $entityManager->flush();

            $message = $isNewEvent ? 'Exposition created successfully!' : 'Exposition edited successfully!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_dashboard');
        }

    
        return $this->render('dashboard/newExpo.html.twig', [
            'formAddExpo' => $form,
            'edit' =>$area->getId(),
        ]);
    }


    // ^ show detail expo (admin)
    #[Route('/dashboard/expo/{slug}', name: 'show_expo_admin')]
    public function show_admin(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
   
        return $this->render('dashboard/showExpo.html.twig', [
            'area' => $area,

        ]);
    }



    // ^ Delete Expo (admin)
    #[Route('/dashboard/expo/{slug}/delete/', name: 'delete_expo')]
    #[isGranted("ROLE_ADMIN")]
    public function delete_expo(Area $area, EntityManagerInterface $entityManager) :Response 
    {
        $entityManager->remove($area);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }

    // ^ AJAX - all archived expostions
    #[Route('/all-past-expositions', name: 'all-past-expositions', methods: ['POST'])]
    public function getPastExpos(Request $request, AreaRepository $areaRepository)
    {

        $pastExpos = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['ARCHIVED'],
        ],
        ['startDate' => 'DESC'] 
        );

        // Convert objects to associative arrays
        $exposArray = [];
        foreach ($pastExpos as $expo) {
            $exposArray[] = [
                'id' => $expo->getId(),
                'name' => $expo->getName(),
                'slug' => $expo->getSlug(),
            ];
        }

        // Convert associative array to JSON and send response
        return new JsonResponse($exposArray);
    }


}
