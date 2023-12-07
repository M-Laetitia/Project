<?php

namespace App\Controller;

use App\Entity\User;
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

        // Récupérez le champ artistInfos en tant que chaîne JSON
        // $artistInfosJson = $user->getArtistInfos();

        // dd($artistInfosJson);
        // Décodez le JSON en tableau associatif
        // $artistInfos = json_decode($artistInfosJson, true);

        // Utilisez $artistInfos comme vous le souhaitez
   
        // dd($emailPro);

        return $this->render('artist/manage.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/artist/{id}/new', name: 'new_artist')]
    #[Route('/artist/{id}/edit', name: 'edit_artist')]
    // injecter en injection de dépendances
    // error avec Picture $picture / Si 'int $pictureId' , en ajustant le typehint de Picture à int dans la signature de la méthode,  Symfony va s'attendre à recevoir l'ID de l'imgen tant que paramètre, plutôt qu'une instance d'entité complète. 
    public function new_edit(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService ) : Response 
    {
        // dd($user);
        $user = $security->getUser();

        // if (!$user instanceof User) {
        //     return $this->redirectToRoute('app_home');
        // }

        $form = $this->createForm(ArtistType::class, $user);
        $form->handleRequest($request);
        $userRoles = $user->getRoles(); // Récupérer les rôles actuels

        if ($form->isSubmitted() && $form->isValid() ) {
            // Ajouter le rôle "ROLE_ARTIST" si ce n'est pas déjà présent
            if (!in_array('ROLE_ARTIST', $userRoles, true)) {
                $userRoles[] = 'ROLE_ARTIST';
            }

            // ^Json infos
            // Récupérer les valeurs pour le champ artistInfos (json)
            $email = $form->get('emailPro')->getData();
            $discipline =$form->get('discipline')->getData();
            // $artistName =$form->get('artistName')->getData();

            // Récupérer ou initialiser artistInfos
            $artistInfos = $user->getArtistInfos() ?? [];

            // Définir les champs et leurs valeurs
            $fields = [
                'emailPro' => $email,
                'discipline' => $discipline,
                // ajouter les autres
            ];

            // Fusionner les champs avec artistInfos
            $artistInfos = array_merge($artistInfos, $fields);

            // Mettez à jour artistInfos dans l'entité User
            $user->setArtistInfos($artistInfos);


            // ^ pictures
            // récupérer les images téléchargées
            $picture = $form->get('pictures')->getData();
            // dd($pictures);

                // on définit le dossier de destination
                $userId = $user->getId();
                $folder = $userId;

                // on appelle le service d'ajout
                $file = $pictureService->add($picture, $folder, 300, 300);
                
                // obtenir l'altDescription associée à cette image
                $altDescription = $form->get('altDescription')->getData();

                $img = new Picture();
                $img->setPath($file);
                $img->setAltDescription($altDescription);
                $img->setType('work');
                // $user->addPicture($picture);
                $img->setUser($user);
                $entityManager->persist($img);
                $entityManager->flush();
            
            // ^ -----------
            // Mise à jour des rôles dans l'entité User
            $user->setRoles($userRoles);
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            // Déconnexion et reconnexion manuelles de l'utilisateur
            // $firewallName = 'main'; 
            // $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            // $this->get('security.token_storage')->setToken($token);

            // Message flash
            $this->addFlash('success', 'Images ajoutées avec succès!');

            return $this->redirectToRoute('manage_artist', ['id' => $user->getId()]);
        }

        return $this->render('artist/new.html.twig', [
            'formAddArtist'=> $form,
            'edit' => $user->getId(),
            'userRoles' => $userRoles,
            'user' => $user
        ]);


    }
    
    #[Route('/delete/picture/{id}', name: 'delete_picture')]
    public function deletePicture(User $user =null, Security $security, Picture $picture, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService ): Response
    {
        // $this->denyAccessUnlessGranted('artist_edit', $user);
        $user = $security->getUser();
        $name = $picture->getPath();

        $userId = $user->getId();
        if($pictureService->delete($name, $userId , 300, 300)) {
            //on supprime l'image de la base données
            $entityManager->remove($picture);
            $entityManager->flush();


            $this->addFlash('success', 'Image deleted successfully.'); // Message flash de succès

            return $this->redirectToRoute('edit_artist', ['id' => $user->getId()]); // Redirection vers une autre page après la suppression
        }
        return $this->redirectToRoute('manage_artist', ['id' => $user->getId()]);
    }
}
