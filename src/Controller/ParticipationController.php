<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Service\MailerService;
use App\Entity\AreaParticipation;
use App\Repository\AreaRepository;
use App\Form\AreaParticipationType;
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


}
