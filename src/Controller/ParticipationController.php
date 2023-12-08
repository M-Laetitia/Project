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
    public function new(AreaParticipation $areaParticipation = null, Area $area, AreaRepository $areaRepository, Security $security, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService) :Response
    {

        // $area = $areaParticipation->
        $user = $security->getUser();
        $areaId = $area->getId();
        $area = $areaRepository->findBy(['id' => $areaId ]);
        // dump($area); die;
        // dump($area[0]); die;
        
        $form = $this->createForm(AreaParticipationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $expoParticipation = $form->getData();
            $expoParticipation->setInscriptionDate(new \DateTimeImmutable());
            $expoParticipation->setUser($user);
            $expoParticipation->setArea($area[0]);

            $entityManager->persist($expoParticipation);
            $entityManager->flush();

            // //send the email
            $userEmail = $user->getEmail();
            $expositionDetails = 'test';
            $mailerService->sendExpositionProposalConfirmation($userEmail, $expositionDetails);

            // ! redirect sur une nouvelle page pour dire que c'est un succès, qu'un mail a été envoyé, + récup pdf
            return $this->redirectToRoute('app_exposition');
        
        }

        return $this->render('exposition/newParticipation.html.twig', [
            'formSendParticipation' => $form,
            'user' => $user,

        ]);
    }
}
