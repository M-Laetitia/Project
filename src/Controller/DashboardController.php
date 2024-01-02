<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AreaRepository;
use App\Repository\UserRepository;
use App\Repository\StudioRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{

    // ^ main page with all event/expo/workshop/studio
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(WorkshopRepository $workshopRepository, AreaRepository $areaRepository, StudioRepository $studioRepository): Response
    {

        // $events = $eventRepository->findBy([]);
        $workshops = $workshopRepository->findBy([]);
        $events = $areaRepository->findBy(['type' => 'EVENT']);
        $expositions = $areaRepository->findBy(['type' => 'EXPO']);
        $studios = $studioRepository->findBy([]);

        $ongoingEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['ARCHIVED'],
        ]);

        $ongoingExpos = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastExpos = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['ARCHIVED'],
        ]);

        $ongoingWorkshop = $workshopRepository->findBy([
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastWorkshop = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ]);

        return $this->render('dashboard/index.html.twig', [
            // 'controller_name' => 'DashboardController',
            'events' => $events,
            'expositions' => $expositions,
            'workshops' => $workshops,
            'studios' => $studios,

            'ongoingEvents' => $ongoingEvents,
            'pastEvents' => $pastEvents,

            'ongoingExpos' => $ongoingExpos,
            'pastExpos' => $pastExpos,

            'ongoingWorkshop' => $ongoingWorkshop,
            'pastWorkshop' => $pastWorkshop,
        ]);
    }

    // ^ list users
    #[Route('/dashboard/index', name: 'list_users')]
    #[IsGranted("ROLE_ADMIN")]
    public function list_users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['username' => 'ASC']);
        return $this->render('dashboard/indexUsers.html.twig', [
            'users' => $users,
        ]);
    }

    // ^ detail user
    #[Route('/dashboard/admin/{slug}/detail_user', name: 'detail_user_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function show_user(User $user): Response
    {
        return $this->render('dashboard/showUser.html.twig', [
            'user' => $user,
        ]);
    }

  
}
