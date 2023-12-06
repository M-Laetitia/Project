<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\UserRepository;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkshopController extends AbstractController
{
    #[Route('/workshop', name: 'app_workshop')]
    public function index(WorkshopRepository $workshopRepository): Response
    {

        $workshops = $workshopRepository->findBy([]);

        return $this->render('workshop/index.html.twig', [
            'workshops' => $workshops,
        ]);
       
    }

    #[Route('/workshop/{id}', name: 'show_workshop')]
    public function show(Workshop $workshop = null): Response
    {

        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }

   
    #[Route('/dashboard/new', name:'new_workshop')]
    #[Route('/dashboard/{id}/edit', name:'edit_workshop')]
    public function new_edit(Workshop $workshop = null, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager ) : Response
    {

        // Fetch users with the specified role
        $users = $userRepository->findUsersbyRole("ROLE_SUPERVISOR");
        
        // dump($users);die;
        if(!$workshop) {
            $workshop = new Workshop();
        }

        $form = $this->createForm(WorkshopType::class, $workshop, [
            'users' => $users,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $workshop = $form->getData();
            $entityManager->persist($workshop);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

    
        return $this->render('dashboard/newWorkshop.html.twig', [
            'formAddWorkshop' => $form,
            'edit' =>$workshop->getId(),
           
        ]);
    }

}
