<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Entity\Timeslot;
use App\Entity\Workshop;
use App\Service\MailerService;
use App\Entity\AreaParticipation;
use App\Entity\ExpositionProposal;
use App\Repository\AreaRepository;
use App\Form\AreaParticipationType;
use App\Entity\WorkshopRegistration;
use App\Form\ExpositionProposalType;
use App\Form\WorkshopRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExpositionProposalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParticipationController extends AbstractController
{
    // #[Route('/participation', name: 'app_participation')]
    // public function index(): Response
    // {
    //     return $this->render('participation/index.html.twig', [
    //         'controller_name' => 'ParticipationController',
    //     ]);
    // }

    // ^ Make a participation for an exposition
    #[Route('/expostion/{id}/new', name: 'new_exposition_participation')]
    public function newExpo(AreaParticipation $areaParticipation = null, Area $area, AreaRepository $areaRepository, Security $security, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService) :Response
    {

        $user = $security->getUser();

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

                return $this->redirectToRoute('app_exposition');

            } else {
                // Redirect / display a message indicating that the maximum number of participants has been reached
                // return $this->render('exposition/maxParticipantsReached.html.twig');
                //! redirection ver detail expo ou liste expo?
                return $this->redirectToRoute('app_exposition');
            }

        
        }

        return $this->render('exposition/newParticipation.html.twig', [
            'formSendParticipation' => $form,
            'user' => $user,

        ]);
    }

    // ^ Make a participation for an event
    #[Route('/event/{id}/new', name: 'new_event_participation')]
    public function newArea(AreaParticipation $areaParticipation = null, Area $area, AreaRepository $areaRepository, Security $security, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService) :Response
    {

        $user = $security->getUser();

        $form = $this->createForm(AreaParticipationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $expoParticipation = $form->getData();
            $expoParticipation->setInscriptionDate(new \DateTimeImmutable());
            $expoParticipation->setUser($user);
            $expoParticipation->setArea($area);

            $entityManager->persist($expoParticipation);
            $entityManager->flush();

            //send the email
            $userEmail = $user->getEmail();
            //  sprintf en PHP est utilisée pour formater une chaîne selon un modèle spécifié. Les %s dans la chaîne de format sont des spécificateurs de format qui indiquent à la fonction sprintf où insérer les valeurs correspondantes dans la chaîne résultante.
            $expositionDetails = sprintf(
                "Name: %s\r\nStartDate:  %s\r\nEndDate: %s\r\nDescription: %s\r\n", 
                $area->getName(),
                $area->getStartDate()->format('Y-m-d H:i:s'),
                $area->getEndDate()->format('Y-m-d H:i:s'),
                $area->getDescription()
            );

            $mailerService->sendEventParticipationConfirmation($userEmail, $expositionDetails);

            $nbReversationRemaining = $area->getNbReversationRemaining();
            if ( $nbReversationRemaining == 0 && $area->getStatus() !== 'CLOSED') {
                // Update the status to "closed"
                $area->setStatus('CLOSED');
                $entityManager->flush();
            } elseif ($nbReversationRemaining > 0 && $area->getStatus() !== 'OPEN') {
                $area->setStatus('OPEN');
                $entityManager->flush();
            }

            // ! redirect sur une nouvelle page pour dire que c'est un succès, qu'un mail a été envoyé, + récup pdf
            $this->addFlash('success', 'You have been successfully registered for this exposition. A confirmation e-mail has been sent to you.');
            return $this->redirectToRoute('app_event');
        
        }

        return $this->render('event/newParticipation.html.twig', [
            'formSendParticipation' => $form,
            'user' => $user,

        ]);
    }

    // ^ Delete a participation  event (admin)
    #[Route('/dashboard/{id}/delete/participation', name: 'delete_participation')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(AreaParticipation $areaParticipation, EntityManagerInterface $entityManager)
    {
        $area =  $areaParticipation->getArea();
        $areaId = $areaParticipation->getArea()->getId();
        $entityManager->remove($areaParticipation);
        $entityManager->flush();

        $nbReversationRemaining = $area->getNbReversationRemaining();
        if ( $nbReversationRemaining == 0 && $area->getStatus() !== 'CLOSED') {
            // Update the status to "closed"
            $area->setStatus('CLOSED');
            $entityManager->flush();
        } elseif ($nbReversationRemaining > 0 && $area->getStatus() !== 'OPEN') {
            $area->setStatus('OPEN');
            $entityManager->flush();
        }

        $this->addFlash('success', 'This participation has been successfully deleted.');
        return $this->redirectToRoute('show_event_admin', ['id' => $areaId]);
    }

    // ^ Delete a participation  expo (admin)
    #[Route('/dashboard/{id}/delete/expo_participation', name: 'delete_expo_participation')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete_participation_expo(AreaParticipation $areaParticipation, EntityManagerInterface $entityManager)
    {
        $area =  $areaParticipation->getArea();
        $areaId = $areaParticipation->getArea()->getId();
        $entityManager->remove($areaParticipation);
        $entityManager->flush();

        $nbReversationRemaining = $area->getNbReversationRemaining();
        if ( $nbReversationRemaining == 0 && $area->getStatus() !== 'CLOSED') {
            // Update the status to "closed"
            $area->setStatus('CLOSED');
            $entityManager->flush();
        } elseif ($nbReversationRemaining > 0 && $area->getStatus() !== 'OPEN') {
            $area->setStatus('OPEN');
            $entityManager->flush();
        }

        $this->addFlash('success', 'This exposition has been successfully deleted.');
        return $this->redirectToRoute('show_expo_admin', ['id' => $areaId]);
    }



    // ^ Make a registration for a workshop
    #[Route('/workshop/{id}/new', name: 'new_workshop_registration')]
    public function newWorkshopRegistration(WorkshopRegistration $workshopRegistration = null, Workshop $workshop, AreaRepository $areaRepository, Security $security, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService) :Response
    {

        // ! if $workshopRegistration
        $user = $security->getUser();

        $form = $this->createForm(WorkshopRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            // Check if the maximum number of participants has been reached
            $maxParticipants = $workshop->getNbRooms();
            $currentParticipants = $workshop->getNbRegistrationMade();

            if ($currentParticipants < $maxParticipants) {

                $workshopRegistration = $form->getData();
                $workshopRegistration->setRegistrationDate(new \DateTimeImmutable());
                $workshopRegistration->setUser($user);
                $workshopRegistration->setWorkshop($workshop);
    
                $entityManager->persist($workshopRegistration);
                $entityManager->flush();
    
                //send the email
                $userEmail = $user->getEmail();
                $expositionDetails = sprintf(
                    "Name: %s\r\nStartDate:  %s\r\nEndDate: %s\r\nDescription: %s\r\n", 
                    $workshop->getName(),
                    $workshop->getStartDate()->format('Y-m-d H:i:s'),
                    $workshop->getEndDate()->format('Y-m-d H:i:s'),
                    $workshop->getDescription()
                );
                $mailerService->sendWorkshopParticipationConfirmation($userEmail, $expositionDetails);
    
                // Check if the maximum number of participants has been reached after the new registration
                $nbReversationRemaining = $workshop->getNbRegistrationRemaining();
                
                if ( $currentParticipants +1 >= $maxParticipants && $workshop->getStatus() !== 'CLOSED') {
                    // Update the status to "closed"
                    $workshop->setStatus('CLOSED');
                    $entityManager->flush();
                } elseif ($currentParticipants < $maxParticipants && $workshop->getStatus() !== 'OPEN') {
                    $workshop->setStatus('OPEN');
                    $entityManager->flush();
                }
    
                // ! redirect sur une nouvelle page pour dire que c'est un succès, qu'un mail a été envoyé, + récup pdf
                $this->addFlash('success', 'Your registration has been successfully processed. You have received a confirmation email.');
                return $this->redirectToRoute('app_workshop');

            } else {
                // Redirect / display a message indicating that the maximum number of participants has been reached
                // return $this->render('exposition/maxParticipantsReached.html.twig');
                return $this->redirectToRoute('app_workshop');
            }

        
        }

        return $this->render('workshop/newParticipation.html.twig', [
            'formSendRegistration' => $form,
            'user' => $user,

        ]);
    }

    // ^ Make a registration for a studio (user)
    #[Route('/studio/{id}/new', name: 'new_registration')]
    public function new_registration(WorkshopRegistration $workshopRegistration = null, Security $security, EntityManagerInterface $entityManager, MailerService $mailerService, Timeslot $timeslot = null, Request $request) : Response
    {

        $user = $security->getUser();

        // if(!$workshopRegistration) {
        //     $workshopRegistration = new WorkshopRegistration();
        // }

        $form= $this->createForm(WorkshopRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopRegistration = $form->getData();
            $workshopRegistration->setUser($user);
            $workshopRegistration->setRegistrationDate(new \DateTimeImmutable());
            $workshopRegistration->setTimeslot($timeslot);
            $entityManager->persist($workshopRegistration);
            $entityManager->flush();

            //send the email
            $userEmail = $user->getEmail();
            $expositionDetails = sprintf(
                "StartDate:  %s\r\nEndDate: %s", 
                $timeslot->getStartDate()->format('Y-m-d H:i:s'),
                $timeslot->getEndDate()->format('Y-m-d H:i:s'),
            );
            $mailerService->sendStudioBookingConfirmation($userEmail, $expositionDetails);

            $this->addFlash('success', 'Your participation has been successfully processed. You have received a confirmation email.');
            return $this->redirectToRoute('app_studio');
        }


        return $this->render('studio/newRegistration.html.twig', [
                'formAddRegistration' =>$form,
                'user' => $user,
                
        ]);
    }



    // ^ Delete a registration -workshop (admin)
    #[Route('/dashboard/{id}/delete/registration', name: 'delete_registration')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete_registration(WorkshopRegistration $workshopRegistration, EntityManagerInterface $entityManager)
    {
        $workshop =  $workshopRegistration->getWorkshop();
        // dump($workshop);die;
        $workshopId = $workshopRegistration->getWorkshop()->getId();

        $entityManager->remove($workshopRegistration);
        $entityManager->flush();

        $nbRegistrationRemaining = $workshop->getNbRegistrationRemaining();
        if ( $nbRegistrationRemaining == 0 && $workshop->getStatus() !== 'CLOSED') {
            // Update the status to "closed"
            $workshop->setStatus('CLOSED');
            $entityManager->flush();
        } elseif ($nbRegistrationRemaining > 0 && $workshop->getStatus() !== 'OPEN') {
            $workshop->setStatus('OPEN');
            $entityManager->flush();
        }

        $this->addFlash('success', 'Registration successfully deleted.');
        return $this->redirectToRoute('show_workshop_admin', ['id' => $workshopId]);
    }

    // ^ Delete a registration - studios (admin)
    #[Route('/dashboard/{id}/delete/registration_studio', name: 'delete_registration_studio')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete_registration_studio(WorkshopRegistration $workshopRegistration, EntityManagerInterface $entityManager)
    {
        $timeslot =  $workshopRegistration->getTimeslot();
        
        $timeslotId = $workshopRegistration->getTimeslot()->getId();

        $nbRoomsStudio = $timeslot->getStudio()->getNbRooms();
        
        $entityManager->remove($workshopRegistration);
        $entityManager->flush();

        $nbRegistrations = $timeslot->getNbRegistrations();
        // dump($nbRegistrations);die;
        $nbRoomsRemaining = $nbRoomsStudio - $nbRegistrations;
        // dump($nbRoomsRemaining);die;

        // if ( $nbRoomsRemaining == 0 && $workshop->getStatus() !== 'CLOSED') {
        //     // Update the status to "closed"
        //     $workshop->setStatus('CLOSED');
        //     $entityManager->flush();
        // } elseif ($nbRegistrationRemaining > 0 && $workshop->getStatus() !== 'OPEN') {
        //     $workshop->setStatus('OPEN');
        //     $entityManager->flush();
        // }

        $this->addFlash('success', 'Registration successfully deleted.');
        return $this->redirectToRoute('studio_dashboard');
    }

    // ^ Make an exposition proposal (artists)
    #[Route('/exposition/{id}/new/', name:'new_exposition_proposal')]
    #[IsGranted("ROLE_ARTIST")]
    // #[Route('/exposition/{id}/edit', name:'edit_workshop_proposal')]
    public function new_edit(Area $area, ExpositionProposal $expositionProposal = null, ExpositionProposalRepository $ExpoProposalRepository, Security $security, Request $request,  EntityManagerInterface $entityManager, MailerService $mailerService ) : Response
    {

        $user = $security->getUser();
        // $hasExistingRequest = $expoProposalRepository->findOneBy(['user' => $user->getId(), 'exposition' => $expositionId]);
        // findby?
        // dump($userId);die;
        if(!$expositionProposal) {
            $expositionProposal = new ExpositionProposal();
        }

        $expositionProposal->setArea($area);
        $form = $this->createForm(ExpositionProposalType::class, $expositionProposal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {




            $expositionProposal = $form->getData();

            $expositionProposal->setProposalDate(new \DateTimeImmutable());
            $expositionProposal->setStatus('pending');
            $expositionProposal->setUser($user);

            $entityManager->persist($expositionProposal);
            $entityManager->flush();

            $nbProposalRemaining = $area->getNbExpositionProposals();
            // dump($nbProposalRemaining);die;
            if ( $nbProposalRemaining < 3 && $area->getStatus() !== 'PENDING') {
                // Update the status to "closed"
                $area->setStatus('PENDING');
                $entityManager->flush();
            } elseif ($nbProposalRemaining >= 3 && $area->getStatus() !== 'OPEN') {
                $area->setStatus('OPEN');
                $entityManager->flush();
            }

            // send the email
            // $username = $user->getUsername();
            $userEmail = $user->getEmail();
            // $registrationDate = new \DateTimeImmutable();
            $expositionDetails = sprintf(
                "Name: %s\r\nStartDate:  %s\r\nEndDate: %s\r\nDescription: %s\r\n", 
                $area->getName(),
                $area->getStartDate()->format('Y-m-d H:i:s'),
                $area->getEndDate()->format('Y-m-d H:i:s'),
                $area->getDescription()
            );
            $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);
            // ---------  


            // Send email to confirm exposition ----------
            // Initialize an array to store associated users
            $usersToNotify = [];
            if ( $nbProposalRemaining == 3 ) {
                // get 'id' in the Area entity
                $expo = $expositionProposal->getArea();
                $expoId = $expo->getId();
                $proposals = $expo->getExpositionProposals();
                
                
                
                foreach ($proposals as $proposal){
                    $user = $proposal->getUser();
                    

                    // Vérifier si l'utilisateur existe et a un e-mail
                    if ($user && $user->getEmail()) {
                        $usersToNotify[] = $user->getEmail();
                    }
                }
                 // Send email
                 $mailerService->sendExpositionConfirmation($expo, $usersToNotify);
            }
            // -----------------------------------


            $this->addFlash('success', 'Your request to participate in this exhibition has been successfully processed. A confirmation e-mail has been sent to you.');
            return $this->redirectToRoute('app_exposition');
        }

    
        return $this->render('exposition/newExpositionProposal.html.twig', [
            'formAddExpoProposal' => $form,
            // 'edit' =>$workshop->getId(),
            // 'hasExistingRequest' => $hasExistingRequest !== null,
           
        ]);
    }
    
    // ^ Delete Artist proposal (admin)

    #[Route('/dashboard/{id}/delete/proposal', name: 'delete_proposal')]
    #[isGranted("ROLE_ADMIN")]
    public function delete_expo_proposal(ExpositionProposal $expoProposal, EntityManagerInterface $entityManager) :Response 
    {

        $area = $expoProposal->getArea();
        // dump($area);die;
        $areaId = $expoProposal->getArea()->getId();
        // dump($expoId);die;

        $entityManager->remove($expoProposal);
        $entityManager->flush();

        $nbProposalRemaining = $area->getNbExpositionProposals();
        // dump($nbProposalRemaining);die;
        if ( $nbProposalRemaining < 3 && $area->getStatus() !== 'PENDING') {
            // Update the status to "closed"
            $area->setStatus('PENDING');
            $entityManager->flush();
        } elseif ($nbProposalRemaining > 3 && $area->getStatus() !== 'OPEN') {
            $area->setStatus('OPEN');
            $entityManager->flush();
        }

        $this->addFlash('success', 'Artist proposal successfully deleted.');
        return $this->redirectToRoute('show_expo_admin', ['id' => $areaId ]);
    
    }

}
