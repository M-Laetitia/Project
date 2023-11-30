<?php

namespace App\Controller;

use App\Form\ArtistType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist')]
    public function index(): Response
    {
        return $this->render('artist/index.html.twig', [
            'controller_name' => 'ArtistController',
        ]);
    }


    #[Route('/artist/{id}', name: 'show_artist')]
    public function show(User $user = null): Response {
    // $user = $security->getUser();

        return $this->render('artist/show.html.twig', [
            'user' => $user,

        ]);
    }


    #[Route('/artist/{id}/manage', name: 'manage_artist')]
    public function manage(User $user = null, Security $security): Response {
    $user = $security->getUser();

        // if (!$user instanceof User) {
        //     return $this->redirectToRoute('app_home');
        // }

        return $this->render('artist/manage.html.twig', [
            'user' => $user,

        ]);
    }

    #[Route('/artist/{id}/new', name: 'new_artist')]
    public function new(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager, ) : Response 
    {

        $user = $security->getUser();

        // if (!$user instanceof User) {
        //     return $this->redirectToRoute('app_home');
        // }

        // dd($user);
        $form = $this->createForm(ArtistType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $userRoles = $user->getRoles(); // Récupérer les rôles actuels

            // Ajouter le rôle "ROLE_ARTIST" si ce n'est pas déjà présent
            if (!in_array('ROLE_ARTIST', $userRoles, true)) {
                $userRoles[] = 'ROLE_ARTIST';
            }

            // Mise à jour des rôles dans l'entité User
            $user->setRoles($userRoles);
            
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            // Déconnexion et reconnexion manuelles de l'utilisateur
            $firewallName = 'main'; 
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('artist/new.html.twig', [
            'formAddArtist'=> $form
        ]);

    }
}
