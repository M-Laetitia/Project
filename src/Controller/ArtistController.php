<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\ArtistType;
use App\Service\PictureService;
use App\Repository\UserRepository;
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
    public function index(UserRepository $userRepository): Response
    {

        $artists = $userRepository->findArtistUsers();
        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }


    #[Route('/artist/{id}', name: 'show_artist')]
    public function show(UserRepository $userRepository, int $id): Response {
    // $user = $security->getUser();

        $artist = $userRepository->findArtistUsers($id);
        // dd($artist);
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,

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
    #[Route('/artist/{id}/edit', name: 'edit_artist')]
    // injecter en injection de dépendances
    public function new_edit(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService ) : Response 
    {

        $user = $security->getUser();

        // if (!$user instanceof User) {
        //     return $this->redirectToRoute('app_home');
        // }

        // dd($user);
        $form = $this->createForm(ArtistType::class, $user);
        $form->handleRequest($request);
        $userRoles = $user->getRoles(); // Récupérer les rôles actuels

        if ($form->isSubmitted() && $form->isValid() ) {

            // Ajouter le rôle "ROLE_ARTIST" si ce n'est pas déjà présent
            if (!in_array('ROLE_ARTIST', $userRoles, true)) {
                $userRoles[] = 'ROLE_ARTIST';
            }

            // récupérer les images téléchargées
            $pictures = $form->get('pictures')->getData();
            // dd($pictures);

            foreach ($pictures as $picture) {
                // on définit le dossier de destination
                $folder = 'products';

                // on appelle le service d'ajout
                $file = $pictureService->add($picture, $folder, 300, 300);
                // die;

                $img = new Picture();
                $img->setUrl($file);
                $img->setAltDescription('');
                // $user->addPicture($picture);
                $img->setUser($user);
                $entityManager->persist($img);
                $entityManager->flush();
            }

            
            // Mise à jour des rôles dans l'entité User
            $user->setRoles($userRoles);
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            // Déconnexion et reconnexion manuelles de l'utilisateur
            // $firewallName = 'main'; 
            // $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            // $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('artist/new.html.twig', [
            'formAddArtist'=> $form,
            'edit' => $user->getId(),
            'userRoles' => $userRoles
        ]);

    }
}
