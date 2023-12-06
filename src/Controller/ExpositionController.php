<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ExpositionProposal;
use App\Form\ExpositionProposalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpositionController extends AbstractController
{
    #[Route('/exposition', name: 'app_exposition')]
    public function index(): Response
    {
        return $this->render('exposition/index.html.twig', [
            'controller_name' => 'ExpositionController',
        ]);
    }

    // ^ Make an exposition proposal (artists)
    #[Route('/exposition/new', name:'new_exposition_proposal')]
    // #[Route('/exposition/{id}/edit', name:'edit_workshop_proposal')]
    public function new_edit(ExpositionProposal $expositionProposal = null, Security $security, Request $request,  EntityManagerInterface $entityManager ) : Response
    {

        $user = $security->getUser();
        $userId = $user->getId();
     
        // dump($userId);die;
        if(!$expositionProposal) {
            $expositionProposal = new ExpositionProposal();
        }

        $form = $this->createForm(ExpositionProposalType::class, $expositionProposal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // $expositionProposal = $form->getData();

            $expositionProposal->setProposalDate(new \DateTimeImmutable());
            $expositionProposal->setStatus('pending');
            $expositionProposal->setUser($user);

            $entityManager->persist($expositionProposal);
            $entityManager->flush();

            return $this->redirectToRoute('app_exposition');
        }

    
        return $this->render('exposition/newExpositionProposal.html.twig', [
            'formAddExpoProposal' => $form,
            // 'edit' =>$workshop->getId(),
           
        ]);
    }

    // ! Add delete exposition proposal
}
