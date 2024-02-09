<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchUserType;
use App\Repository\AreaRepository;
use App\Repository\UserRepository;
use App\Repository\StudioRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function list_users(UserRepository $userRepository, Request $request, SessionInterface $session): Response
    {

        $formUserSearch = $this->createForm(SearchUserType::class);
        $formUserSearch->handleRequest($request);
        $searchResults = [];
        $role = $request->query->get('role');
        // dd($role);

        $redirectToSamePage = false; // Flag to determine if redirection is needed

        if ($role) {
            // Use discipline to filter artists
            $searchResults = $userRepository->findUsersbyRole($role);
            // dd($searchResults);
            // Store search results in session
            $session->set('searchResults', $searchResults);
            $redirectToSamePage = true;
             
        } else {
            if ($formUserSearch->isSubmitted() && $formUserSearch->isValid()) {
                $username = $formUserSearch->get('username')->getData();
    
                $searchResults = $userRepository->findArtistByUsername($username);
                
                $redirectToSamePage = true;
            }
        }
 
        if ($redirectToSamePage) {
            // Store search results in session if needed
            $session->set('searchResults', $searchResults);
            // Redirect to the same page to avoid form resubmission
            return $this->redirectToRoute('list_users');
        }
    
        // Retrieve search results from session
        if ($session->has('searchResults')) {
            $searchResults = $session->get('searchResults');
            $session->remove('searchResults'); // Remove search results from session after use
        }
    
        // $users = $userRepository->findBy([], ['username' => 'ASC']);
        return $this->render('dashboard/indexUsers.html.twig', [
            // 'users' => $users,
            'users' => $searchResults ?: $userRepository->findBy([], ['username' => 'ASC']), // Use all users if no search result
            'formUserSearch' => $formUserSearch->createView(),
            'searchResults' => $searchResults ? true : false, // Set to true if there are search results, otherwise false
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

    // //^ search user (by username)
    // #[Route('/artist/search/username/{username}', name: 'app_artist_search_username', methods: ['GET'])]
    // public function searchByUsername(UserRepository $userRepository, string $username): Response
    // {
    //     // Effectuer la recherche par pseudo en fonction des critères
    //     $userSearch = $userRepository->findUserByUsername($username);

    //     // Rediriger vers une nouvelle page avec les résultats de la recherche
    //     return $this->render('artist/searchResults.html.twig', [
    //         'userSearch' => $userSearch,
    //     ]);
    // }



  
}
