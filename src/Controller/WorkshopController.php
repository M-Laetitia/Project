<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\UserRepository;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WorkshopRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkshopController extends AbstractController
{
    #[Route('/workshop', name: 'app_workshop')]
    public function index(WorkshopRepository $workshopRepository): Response
    {

        $workshops = $workshopRepository->findBy([]);

        $ongoingWorkshop = $workshopRepository->findBy([
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastWorkshop = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ]);



        return $this->render('workshop/index.html.twig', [
            'workshops' => $workshops,
            'ongoingWorkshop' => $ongoingWorkshop,
            'pastWorkshop' => $pastWorkshop,
        ]);
       
    }

    // ^ show workshop (user)
    #[Route('/workshop/{slug}', name: 'show_workshop')]
    public function show(Workshop $workshop = null, Security $security, WorkshopRegistrationRepository $workshopRegistrationRepository ): Response
    {

        $user = $security->getUser();
        $userId = $user->getId();
        $workshopId = $workshop->getId();

        $existingRegistration = [];

        $hasExistingRegistration = $workshopRegistrationRepository->findOneBy(['user' => $user->getId(), 'workshop' => $workshopId]);
        $existingRegistration = $hasExistingRegistration !== null;
        // dump($existingRegistration);die;

        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
            'existingRegistration' => $existingRegistration,
            'user' => $user,
        ]);
    }

    // ^ show workshop (admin)
    #[Route('/dashboard/show/{slug}/workshop/', name: 'show_workshop_admin')]
    public function show_admin(Workshop $workshop = null): Response
    {

        return $this->render('dashboard/showWorkshop.html.twig', [
            'workshop' => $workshop,
        ]);
    }
   
    // ^ Create/Edit workshop (admin)
    #[Route('/dashboard/new/workshop', name:'new_workshop')]
    #[Route('/dashboard/{slug}/edit/workshop', name:'edit_workshop')]
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
            
            // dump($workshop);die;

            // // Transform the workshop data to Workshop entity
            // $workshopId = $data['workshop'];
            // $workshop = $this->entityManager->getRepository(Workshop::class)->find($workshopId);

            // // Set the Workshop entity back to the form data
            // $data['workshop'] = $workshop;

            $workshop->setStatus('OPEN');
            $workshop = $form->getData();
            $workshop->setSlug($workshop->generateSlug());
            $entityManager->persist($workshop);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

    
        return $this->render('dashboard/newWorkshop.html.twig', [
            'form' => $form,
            'edit' =>$workshop->getId(),
            'workshopId' => $workshop->getId(),
           
        ]);
    }

    // ^ Delete workshop (admin)
    #[Route('/dashboard/{slug}/delete/workshop', name:'delete_workshop')] 
    public function delete(Workshop $workshop, EntityManagerInterface $entityManager) :Response
    {

        $area = $workshop->getArea(); 
        // dump($workshop);die;
        $entityManager->remove($workshop);
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

        return $this->redirectToRoute('app_dashboard');
    }

}



// Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa