<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Entity\Workshop;
use App\Service\MailerService;
use App\Entity\AreaParticipation;
use App\Repository\AreaRepository;
use App\Form\AreaParticipationType;
use App\Entity\WorkshopRegistration;
use App\Form\WorkshopRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        // $areaId = $area->getId();
        // $area = $areaRepository->findBy(['id' => $areaId ]);

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
                $expositionDetails = 'test';
                $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);
    
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
                return $this->redirectToRoute('app_exposition');

            } else {
                // Redirect / display a message indicating that the maximum number of participants has been reached
                // return $this->render('exposition/maxParticipantsReached.html.twig');
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
            $expositionDetails = 'test';
            $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);

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
            return $this->redirectToRoute('app_event');
        
        }

        return $this->render('exposition/newParticipation.html.twig', [
            'formSendParticipation' => $form,
            'user' => $user,

        ]);
    }

    // ^ Delete a participation (admin)
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


        return $this->redirectToRoute('show_event_admin', ['id' => $areaId]);
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
                $expositionDetails = 'test';
                $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);
    
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
    public function new_registration(WorkshopRegistration $workshopRegistration, Timeslot $timeslot, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService) 
    {

        $user = $security->getUser();


        if(!$workshopRegistration) {
            $workshopRegistration = new WorkshopRegistration();
        }

        $form= $this->createForm(WorkshopRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopRegistration = $form->getData();
            $workshopRegistration->setUser($user);
            $workshopRegistration->setWorkshop($timeslot);
            $entityManager->persist($workshopRegistration);
            $entityManager->flush();

            //send the email
            $userEmail = $user->getEmail();
            $expositionDetails = 'test';
            $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);

            return $this->redirectToRoute('app_workshop');
        }


        return $this->render('studio/newRegistration.html.twig', [
                'formAddRegistration' =>$form,
                
        ]);
    }



    // ^ Delete a registration -workshop / studios (admin)
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


        return $this->redirectToRoute('show_workshop_admin', ['id' => $workshopId]);
    }
    


}
