<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchUserType;
use App\Repository\AreaRepository;
use App\Repository\UserRepository;
use App\Repository\StudioRepository;
use App\Form\PublishedArtistPageType;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{

    // ^ main page with all event/expo/workshop/studio
    #[Route('/admin/dashboard', name: 'app_dashboard')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(WorkshopRepository $workshopRepository, AreaRepository $areaRepository, StudioRepository $studioRepository): Response
    {

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
    #[Route('/admin/dashboard/index', name: 'list_users')]
    #[IsGranted("ROLE_ADMIN")]
    public function list_users(UserRepository $userRepository, Request $request, SessionInterface $session, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $formUserSearch = $this->createForm(SearchUserType::class);
        $formUserSearch->handleRequest($request);
        $searchResults = [];
        $role = $request->query->get('role');
        $sortBy = $request->query->get('sortBy');
        $artists = $userRepository->findBy(['roles' => 'ROLE_ARTIST']);

        $datas = $userRepository->findBy([], ['username' => 'ASC']);

        $users = $paginator->paginate(
            $datas, // Query with the datas to paginate (= users)
            $request->query->getInt('page', 1), // number of the current page
            6 // nb of results per page
        );
    
    

        $redirectToSamePage = false; // Flag to determine if redirection is needed
        if ($role) {
            $searchResults = $userRepository->findUsersbyRole($role);
            $redirectToSamePage = true;
        } elseif ($formUserSearch->isSubmitted() && $formUserSearch->isValid()) {
            $username = $formUserSearch->get('username')->getData();
            $searchResults = $userRepository->findArtistByUsername($username);
            $redirectToSamePage = true;
        } elseif ($sortBy) {
            if ($sortBy === 'username_asc') {
                $searchResults = $userRepository->findBy([], ['username' => 'ASC']);
                $redirectToSamePage = true;
            } elseif ($sortBy === 'username_desc') {
                $searchResults = $userRepository->findBy([], ['username' => 'DESC']);
                $redirectToSamePage = true;
            } elseif ($sortBy === 'created_at_asc') {
                $searchResults = $userRepository->findBy([], ['registrationDate' => 'ASC']);
                $redirectToSamePage = true;
            } elseif ($sortBy === 'created_at_desc') {
                $searchResults = $userRepository->findBy([], ['registrationDate' => 'DESC']);
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
            'users' => $searchResults ?: $users, // Use all users if no search result
            'formUserSearch' => $formUserSearch->createView(),
            'searchResults' => $searchResults ? true : false, // Set to true if there are search results, otherwise false
            
        ]);
    }

    // ^ detail user
    #[Route('/admin/dashboard/user/{slug}/detail_user', name: 'detail_user_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function show_user(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {

        $formPage = $this->createForm(PublishedArtistPageType::class);
        $formPage->handleRequest($request);


        if ($formPage->isSubmitted() && $formPage->isValid() ) {

                if ($this->isGranted('ROLE_ADMIN')) {
                        if ($user->getIsPublished() == 1) {
                            $user->setIsPublished(-1);
                            $message = 'Artist page successfully moderated!';
                        } else {
                            $user->setIsPublished(1);
                            $message = 'Artist page successfully published!';
                        }
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $this->addFlash('success', $message);
                        return $this->redirectToRoute('detail_user_admin',  ['slug' => $user->getSlug()]);

                    
                } else {
                    $this->addFlash('error', 'You do not have permission to censor this artist page.');
                    return $this->redirectToRoute('app_home');

                }
            
        }


        return $this->render('dashboard/showUser.html.twig', [
            'user' => $user,
            'formPublishPageArtist' => $formPage,
        ]);
    }

    // ^ list events
    #[Route('/admin/dashboard/events', name: 'list_events')]
    #[IsGranted("ROLE_ADMIN")]
    public function indexEvents(AreaRepository $areaRepository, StudioRepository $studioRepository): Response
    {
 
        $events = $areaRepository->findBy(['type' => 'EVENT']);
 
        $ongoingEvents = $areaRepository->findBy([
             'type' => 'EVENT',
             'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['ARCHIVED'],
        ]);

         
 
         return $this->render('dashboard/indexEvents.html.twig', [
             'events' => $events,


             'ongoingEvents' => $ongoingEvents,
             'pastEvents' => $pastEvents,

         ]);
    }

    // ^ list expos
    #[Route('/admin/dashboard/expositions', name: 'list_expos')]
    #[IsGranted("ROLE_ADMIN")]
    public function indexExpos(AreaRepository $areaRepository, StudioRepository $studioRepository): Response
    {
    
        $events = $areaRepository->findBy(['type' => 'EXPO']);
    
        $ongoingEvents = $areaRepository->findBy([
                'type' => 'EXPO',
                'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $areaRepository->findBy([
            'type' => 'EXPO',
            'status' => ['ARCHIVED'],
        ]);

    
            return $this->render('dashboard/indexExpos.html.twig', [
                'events' => $events,
                'ongoingEvents' => $ongoingEvents,
                'pastEvents' => $pastEvents,

            ]);
    }

    // ^ list expos
    #[Route('/admin/dashboard/workshops', name: 'list_workshops')]
    #[IsGranted("ROLE_ADMIN")]
    public function indexWorkshops(WorkshopRepository $workshopRepository, StudioRepository $studioRepository): Response
    {
    
        $events = $workshopRepository->findBy([]);
    
        $ongoingEvents = $workshopRepository->findBy([
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ]);

    
            return $this->render('dashboard/indexWorkshops.html.twig', [
                'events' => $events,
                'ongoingEvents' => $ongoingEvents,
                'pastEvents' => $pastEvents,

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
