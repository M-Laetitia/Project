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
    #[Route('/dashboard/expo/{id}', name: 'show_expo_admin')]
    public function show_admin(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
   
        return $this->render('dashboard/showExpo.html.twig', [
            'area' => $area,

        ]);
    }




    #[Route('/dashboard/new/expo', name:'new_expo')]
    #[Route('/dashboard/{id}/edit/expo', name:'edit_expo')]
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

    // ^ Delete Expo (admin)

    #[Route('/dashboard/{id}/delete/expo', name: 'delete_expo')]
    #[isGranted("ROLE_ADMIN")]
    public function delete_expo(Area $area, EntityManagerInterface $entityManager) :Response 
    {
        $entityManager->remove($area);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }


}
