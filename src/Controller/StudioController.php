<?php

namespace App\Controller;

use App\Entity\Timeslot;
use App\Form\TimeSlotType;
use App\Repository\StudioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudioController extends AbstractController
{
    // ^ list art studios (user)
    #[Route('/studio', name: 'app_studio')]
    public function index(StudioRepository $studioRepository): Response
    {
        $studios = $studioRepository->findBy([]);

        return $this->render('studio/index.html.twig', [
            'studios' => $studios,

        ]);
    }

    // ^ list art studios / planning (supervisor + admin)
    #[Route('/studio/dashboard', name: 'studio_dashboard')]
    public function index_dashboard(StudioRepository $studioRepository): Response
    {
        $studios = $studioRepository->findBy([]);

        return $this->render('studio/supervisorDashboard.html.twig', [
            'studios' => $studios,

        ]);
    }

    // ^ Create timeslot (supervisor)
    #[Route('/studio/{id}/dashboard', name: 'new_timeslot')]
    public function new(Timeslot $timeslot = null, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {

        $user = $security->getUser();

        
        if(!$timeslot) {
            $timeslot = new TimeSlot();
        }

        $form = $this->createForm(TimeSlotType::class, $timeslot );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) 
        {
            $timeslot->setUser($user);
            $timeslot = $form->getData();
            $entityManager->persist($timeslot);
            $entityManager->flush();

            return $this->redirectToRoute('studio_dashboard');
        }


        return $this->render('studio/newTimeSlot.html.twig', [
            'formAddTimeslot' => $form,

        ]);
    }
}
