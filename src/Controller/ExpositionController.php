<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Service\MailerService;
use App\Entity\ExpositionProposal;
use App\Repository\AreaRepository;
use App\Form\ExpositionProposalType;
use App\Controller\ExpositionController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExpositionProposalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpositionController extends AbstractController
{
    #[Route('/exposition', name: 'app_exposition')]
    public function index(AreaRepository $areaRepository, ExpositionProposalRepository $expoProposalRepository, MailerService $mailerService, Security $security): Response
    {
        $user = $security->getUser();    

        // Fetch all expo from the AreaRepository
        $expos = $areaRepository->findBy(['type' => 'EXPO']);

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
}
