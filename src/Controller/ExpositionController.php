<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Form\ExpositionType;
use App\Service\MailerService;
use App\Entity\ExpositionProposal;
use App\Repository\AreaRepository;
use App\Form\ExpositionProposalType;
use App\Controller\ExpositionController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AreaParticipationRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExpositionProposalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpositionController extends AbstractController
{
    // ^ Show list expos
    #[Route('/exposition', name: 'app_exposition')]
    public function index(AreaRepository $areaRepository, ExpositionProposalRepository $expoProposalRepository, MailerService $mailerService, Security $security): Response
    {
        $user = $security->getUser();    

        // Fetch all expo from the AreaRepository
        $expos = $areaRepository->findBy(['type' => 'EXPO']);

        //^recup nb de réservation - initialisation tableau vide
        // ! indiquer nb restant
        $reservationCounts = [];

        // Initialize arrays to store existing proposals and proposal counts
        $existingProposals = [];
        $proposalCounts = [];

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

            // dump($usersToNotify);die;

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
                $mailerService->sendExpositionConfirmationEmail($expo, $usersToNotify);
            }
        }
        
        return $this->render('exposition/index.html.twig', [
            'expos' => $expos, 
            'existingProposals' => $existingProposals,
            'proposalCounts' => $proposalCounts,
            'reservationCounts' => $reservationCounts,
        ]);
    }

    // ^ Show detail expo (user)
    #[Route('/exposition/{id}', name: 'show_exposition')]
    public function show(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {

        $user = $security->getUser();
        $userId = $user->getId();
        $areaId = $area->getId();

        $existingParticipation = [];
        // ! voir pour utiliser dql
        // $existingParticipation = $areaParticipationRepository->checkIfUserHasExistingParticipation($userId, $areaId);

        $hasExistingParticipation = $areaParticipationRepository->findOneBy(['user' => $user->getId(), 'area' => $areaId]);
        $existingParticipation = $hasExistingParticipation !== null;

        // sets $existingParticipation to true if $hasExistingParticipation is not null, and to false otherwise.
        // $existingParticipation = $hasExistingParticipation !== null ?? false;

    
        return $this->render('exposition/show.html.twig', [
            'area' => $area,
            'existingParticipation' => $existingParticipation
        ]);
    }

    // ^ show detail expo (admin)
    #[Route('/dashboard/{id}', name: 'show_expo_admin')]
    public function show_admin(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
   
        return $this->render('dashboard/showExpo.html.twig', [
            'area' => $area,

        ]);
    }



    // ^ Make an exposition proposal (artists)
    #[Route('/exposition/{id}/new/', name:'new_exposition_proposal')]
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

            // send the email
            // $username = $user->getUsername();
            $userEmail = $user->getEmail();
            // $registrationDate = new \DateTimeImmutable();
            $expositionDetails = 'test';
            $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);
            // ---------  

            return $this->redirectToRoute('app_exposition');
        }

    
        return $this->render('exposition/newExpositionProposal.html.twig', [
            'formAddExpoProposal' => $form,
            // 'edit' =>$workshop->getId(),
            // 'hasExistingRequest' => $hasExistingRequest !== null,
           
        ]);
    }

    // ! Add delete exposition proposal

    #[Route('/dashboard/new/expo', name:'new_expo')]
    #[Route('/dashboard/{id}/edit/expo', name:'edit_expo')]
    public function new_edit_Expo(Area $area = null, Request $request, EntityManagerInterface $entityManager ) : Response
    {

        if(!$area) {
            $area = new Area();
        }

        $form= $this->createForm(ExpositionType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $area->setType('EXPO');
            $area = $form->getData();
            $entityManager->persist($area);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

    
        return $this->render('dashboard/newExpo.html.twig', [
            'formAddExpo' => $form,
            'edit' =>$area->getId(),
        ]);
    }

    // ^ Delete Expo (admin)

    #[Route('/dashboard/{id}/delete/expo', name: 'delete_expo')]
    public function delete_expo(Area $area, EntityManagerInterface $entityManager) :Response 
    {
        $entityManager->remove($area);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }


}
