<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Picture;
use App\Form\ArtistType;
use App\Form\EditArtistType;
use App\Form\SearchArtistType;
use App\Service\PictureService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ArtistController extends AbstractController
{
   #[Route('/artist', name: 'app_artist')]
public function index(UserRepository $userRepository, Request $request): Response
{
    $formArtistSearch = $this->createForm(SearchArtistType::class);
    $formArtistSearch->handleRequest($request);

    $artistsSearch = [];

    if ($formArtistSearch->isSubmitted() && $formArtistSearch->isValid()) {
        $criteria = $formArtistSearch->getData();
        $artistsSearch = $userRepository->findArtistByCriteria($criteria);
        
        // Stocker les résultats de la recherche en session
        $request->getSession()->set('artistsSearch', $artistsSearch);
    }

    // Effacer les résultats de la recherche si la page est chargée via la méthode GET
    if ($request->getMethod() === 'GET') {
        $request->getSession()->remove('artistsSearch');
    } else {
        // Si la page est chargée via une autre méthode (par exemple POST), récupérez les résultats de la recherche depuis la session
        $artistsSearch = $request->getSession()->get('artistsSearch', []);
    }

    $artists = $userRepository->findArtistUsers();

    return $this->render('artist/index.html.twig', [
        'artists' => $artists,
        'formArtistSearch' => $formArtistSearch->createView(),
        'artistsSearch' => $artistsSearch,
    ]);
}


    // ^ show artist detail (all)
    #[Route('/artist/{slug}-{id}', name: 'show_artist')]
    public function show(UserRepository $userRepository, string $slug, int $id): Response {

        // get the artist
        $artist = $userRepository->findArtistUsers($id);
        // check if the artist exists
        if (!$artist) {
            // if not, redirect to the error page
            return $this->render('error/error404.html.twig', [], new Response('', Response::HTTP_NOT_FOUND));
        }

        $artist = $userRepository->findArtistUsers($id);
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,

        ]);
    }

    // ^ artist page -manage  (ROLE ARTIST)
    #[Route('/artist/{slug}/manage', name: 'manage_artist')]
    #[IsGranted("ROLE_ARTIST")]
    public function manage(User $user = null, Security $security, EntityManagerInterface $entityManager,  Request $request): Response 
    {
        $user = $security->getUser();
        $artistInfos = $user->getArtistInfos() ?? [];
        $form = $this->createForm(EditArtistType::class, $artistInfos, [
            'data_class' => null,
        ]);
        
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            // ^ Json infos
            $email = $form->get('emailPro')->getData();
            $discipline =$form->get('discipline')->getData();
            $artistName =$form->get('artistName')->getData();

            
            // Vérifier les valeurs existantes avant de les mettre à jour
            $fields = [];

            if ($email !== null && $email !== $artistInfos['emailPro']) {
                $fields['emailPro'] = $email;
            }

            if ($discipline !== null && $discipline !== $artistInfos['discipline']) {
                $fields['discipline'] = $discipline;
            }

            if ($artistName !== null && $artistName !== $artistInfos['artistName']) {
                $fields['artistName'] = $artistName;
            }
            

            // Fusionner les champs avec artistInfos
            $artistInfos = array_merge($artistInfos, $fields);

            // Mettez à jour artistInfos dans l'entité User
            $user->setArtistInfos($artistInfos);

            // ^ -----------

            $entityManager->flush();

            $this->addFlash('success', 'Informations successfully edited!');
            return $this->redirectToRoute('manage_artist', ['id' => $user->getId()]);
        }

        return $this->render('artist/manage.html.twig', [
            'user' => $user,
            'formEditArtist'=> $form,
        ]);
    }



    #[Route('/artist/{id}/new', name: 'new_artist')]
    #[Route('/artist/{id}/edit', name: 'edit_artist')]
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

            // Récupérer ou initialiser artistInfos
            // $artistInfos = array_merge($artistInfos, $fields);
            $artistInfos = $user->getArtistInfos() ?? [];

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
                if ($picture !== null) 
                {
                    $file = $pictureService->add($picture, $folder, 300, 300);
                    $altDescription = $form->get('altDescription')->getData();
                    $img = new Picture();
                    $img->setPath($file);
                    $img->setAltDescription($altDescription);
                    $img->setType('work');
                    $img->setUser($user);
                    $entityManager->persist($img);
                    $entityManager->flush();

                    $this->addFlash('success', 'Images ajoutées avec succès!');
                }
                
            
            // ^ -----------

          
            // Mise à jour des rôles dans l'entité User

            $user->setRoles($userRoles);
            $user = $form->getData();
            $entityManager->persist($user);
            $user->setSlug($area->generateSlug());
            $entityManager->flush();

            // Déconnexion et reconnexion manuelles de l'utilisateur
            // $firewallName = 'main'; 
            // $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            // $this->get('security.token_storage')->setToken($token);

            // Message flash
            $this->addFlash('success', 'Profil artist successfully created');

            return $this->redirectToRoute('app_home');
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
