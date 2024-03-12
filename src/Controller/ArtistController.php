<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Picture;
use App\Form\ArtistType;
use App\Form\BannerFormType;
use App\Form\EditArtistType;
use App\Form\PictureFormType;
use App\Form\ArtistStatusType;
use App\Form\SearchArtistType;
use App\Service\PictureService;
use App\Repository\UserRepository;
use App\Form\PublishedArtistPageType;
use App\Repository\ContactRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
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
    // #[Route('/artist', name: 'app_artist')]
    // public function index(UserRepository $userRepository, Request $request): Response
    // {
    //     $formArtistSearch = $this->createForm(SearchArtistType::class);
    //     $formArtistSearch->handleRequest($request);
    
    //     if ($formArtistSearch->isSubmitted() && $formArtistSearch->isValid()) {
    //         $user = $formArtistSearch->getData(); // Récupérer l'objet User depuis le formulaire
    //         $username = $user->getUsername(); // Récupérer le nom d'utilisateur depuis l'objet User

    //         $discipline = $formArtistSearch->get('discipline')->getData();

    //         if (!empty($username)) {
    //             return $this->redirectToRoute('app_artist_search_username', ['username' => $username]);
    //         } elseif (!empty($discipline)) {
    //             return $this->redirectToRoute('app_artist_search_discipline', ['discipline' => $discipline]);
    //         }

    //     }
    
    //     $artists = $userRepository->findArtistUsers();
    
    //     return $this->render('artist/index.html.twig', [
    //         'artists' => $artists,
    //         'formArtistSearch' => $formArtistSearch->createView(),
    //     ]);
    // }

    #[Route('/artist/new', name: 'get_role_artist')]
    public function newArtist(UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager,  Request $request): Response
    {

        if($user = $security->getUser()) {

            $form = $this->createForm(ArtistStatusType::class, $user);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid() ) {
                // Ajouter le rôle "ROLE_ARTIST" si ce n'est pas déjà présent
                if (!in_array('ROLE_ARTIST', $userRoles, true)) {
                    $userRoles[] = 'ROLE_ARTIST';
                }

                $userSlug = $user->getSlug();
    
                // ^Json infos
                // Récupérer les valeurs pour le champ artistInfos (json)
                $email = $form->get('emailPro')->getData();
                $discipline =$form->get('discipline')->getData();
                $artistName =$form->get('artistName')->getData();
                $category =$form->get('category')->getData();
    
                // Récupérer ou initialiser artistInfos
                $artistInfos = $user->getArtistInfos() ?? [];
    
         
                // Définir les champs et leurs valeurs
                $fields = [
                    'emailPro' => $email,
                    'discipline' => $discipline,
                    'artistName' => $artistName,
                    'category' => $category,
    
                ];
    
                // Fusionner les nouvelles données avec les données existantes
                $artistInfos = array_merge($artistInfos, $fields);
                // dd($artistInfos);
    
                // Mise à jour artistInfos dans l'entité User
                $user->setArtistInfos($artistInfos);
    
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
                $this->addFlash('success', 'Status artist successfully created');
    
                return $this->redirectToRoute('manage_profil' , ['slug' => $userSlug]);
            }
        
            
            return $this->render('artist/newArtist.html.twig', [
                'user' => $user,
                'formStatusArtist'=> $form,
            ]);
        }

        return $this->render('artist/newArtist.html.twig', [
        ]);
        

    }


    #[Route('/artist', name: 'app_artist')]
    public function index(UserRepository $userRepository, Request $request, SessionInterface $session): Response
    {
        $formArtistSearch = $this->createForm(SearchArtistType::class);
        $formArtistSearch->handleRequest($request);
        $searchResults = [];
        $disciplines = $userRepository->findAllDisciplines();
        // Check if discipline is selected in the GET request
        $discipline = $request->query->get('discipline');
        $redirectToSamePage = false; // Flag to determine if redirection is needed

        if ($discipline) {
            // Use discipline to filter artists
            $searchResults = $userRepository->findArtistByDisciplineFilter($discipline);
            // Store search results in session
            $session->set('searchResults', $searchResults);
            $redirectToSamePage = true;
             
        } else {
            // If no discipline is selected in the GET request,
            // check if the form is submitted and valid
            if ($formArtistSearch->isSubmitted() && $formArtistSearch->isValid()) {
                $username = $formArtistSearch->get('username')->getData();
                $discipline = $formArtistSearch->get('discipline')->getData();
    
                if (!empty($username) && !empty($discipline)) {
                    // Combine results from both queries
                    $artistsByUsername = $userRepository->findArtistByUsername($username);
                    $artistsByDiscipline = $userRepository->findArtistByDiscipline($discipline);
                    $searchResults = array_merge($artistsByUsername, $artistsByDiscipline);
                } elseif (!empty($username)) {
                    $searchResults = $userRepository->findArtistByUsername($username);
                } elseif (!empty($discipline)) {
                    $searchResults = $userRepository->findArtistByDiscipline($discipline);
                }
                $redirectToSamePage = true;
            }
        }
        
        if ($redirectToSamePage) {
            // Store search results in session if needed
            $session->set('searchResults', $searchResults);
            // Redirect to the same page to avoid form resubmission
            return $this->redirectToRoute('app_artist');
        }
    
        // Retrieve search results from session
        if ($session->has('searchResults')) {
            $searchResults = $session->get('searchResults');
            $session->remove('searchResults'); // Remove search results from session after use
        }
    
        return $this->render('artist/index.html.twig', [
            'artists' => $searchResults ?: $userRepository->findArtistUsers(), // Use all artists if no search result
            'formArtistSearch' => $formArtistSearch->createView(),
            'searchResults' => $searchResults ? true : false, // Set to true if there are search results, otherwise false
            'disciplines' => $disciplines,

        ]);
    }

    //^ search artist (by username)
    #[Route('/artist/search/username/{username}', name: 'app_artist_search_username', methods: ['GET'])]
    public function searchByUsername(UserRepository $userRepository, string $username): Response
    {
        // Effectuer la recherche par pseudo en fonction des critères
        $artistsSearch = $userRepository->findArtistByUsername($username);

        // Rediriger vers une nouvelle page avec les résultats de la recherche
        return $this->render('artist/searchResults.html.twig', [
            'artistsSearch' => $artistsSearch,
        ]);
    }


    //^ search artist (by discipline)
    #[Route('/artist/search/discipline/{discipline}', name: 'app_artist_search_discipline', methods: ['GET'])]
    public function searchByDiscipline(UserRepository $userRepository, Request $request): Response
    {

        $discipline = $request->attributes->get('discipline');
        
        // Effectuer la recherche par pseudo en fonction des critères
        $artistsSearch = $userRepository->findArtistByDiscipline($discipline);

        // Rediriger vers une nouvelle page avec les résultats de la recherche
        return $this->render('artist/searchResults.html.twig', [
            'artistsSearch' => $artistsSearch,
        ]);
    }

    //^ search artist (by filters)
    #[Route('/artists/filter', name: 'app_artists_filter', methods: ['GET'])]
    public function filterArtistsByDiscipline(UserRepository $userRepository, Request $request): Response
    {
        $discipline = $request->query->get('discipline'); 
        // dd($discipline);
        $artistsSearch = $userRepository->findArtistByDiscipline($discipline);

        return $this->render('artist/index.html.twig', [
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


    // ^ artist page -manage  (ROLE ARTIST)
    #[Route('/artist/{slug}/artist_profil', name: 'manage_profil')]
    #[IsGranted("ROLE_ARTIST")]
    public function manageArtistProfil(User $user = null, Security $security, EntityManagerInterface $entityManager, ContactRepository $contactRepo , PictureRepository $pictureRepo, PictureService $pictureService,  Request $request): Response 
    {

        $artist = $security->getUser();
        $artistId = $artist->getId();
        $artistInfos = $user->getArtistInfos() ?? [];
        $artistSocials = $artist->getContacts();

        $existingBanner = $pictureRepo->findOneBy(['user' => $artistId, 'type' => 'banner']); 
        $bannerExists =  $existingBanner->getPath();

        $contactInstagram = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Instagram']);
        $contactBehance = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Behance']);
        $contactFacebook = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Facebook']);
      
        // Initialiser les variables avant la boucle
        $instagram = null;
        $behance = null;
        $facebook = null;

        foreach ($artistSocials as $social) {
            if ($social->getName() == 'Instagram') {
                $instagram = $social->getUrl();
            } elseif ($social->getName() == 'Behance') {
                $behance = $social->getUrl();
            } elseif ($social->getName() == 'Facebook') {
                $facebook = $social->getUrl();
            }
        }

        $form = $this->createForm(ArtistType::class, $artistInfos, [
            'data_class' => null,
        ]);


        $form->handleRequest($request);

        $formPage = $this->createForm(PublishedArtistPageType::class);
        $formPage->handleRequest($request);


       
        if ($form->isSubmitted() && $form->isValid() ) {
            // ^ Json infos
            $email = $form->get('emailPro')->getData();
            $discipline =$form->get('discipline')->getData();
            $artistName =$form->get('artistName')->getData();

            $artistInstagram = $form->get('instagram')->getData();
            $artistBehance = $form->get('behance')->getData();
            $artistFacebook = $form->get('facebook')->getData();
 
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

            // Si pas de facebook pour cet utilisateur et ce réseau social
            if (!$contactFacebook) {
                // Créez une nouvelle instance de l'entité Contact
                $contactFacebook = new Contact();
                $contactFacebook->setUser($artist); // Associez l'utilisateur à ce contactFacebook
                $contactFacebook->setName("Facebook"); // Définissez le nom du réseau social
                $contactFacebook->setIcon('<i class="fa-brands fa-facebook"></i>');
                $contactFacebook->setUrl($artistFacebook);
                $entityManager->persist($contactFacebook);
                $entityManager->flush($contactFacebook);
            }

            $contactBehance->setUrl($artistBehance);
         
            // Fusionner les champs avec artistInfos
            $artistInfos = array_merge($artistInfos, $fields);
            // Mettez à jour artistInfos dans l'entité User
            $entityManager->persist($user);
            $user->setArtistInfos($artistInfos);
            $entityManager->flush();

            $this->addFlash('success', 'Informations successfully edited!');
            return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
        }

        if ($formPage->isSubmitted() && $formPage->isValid() ) {

            if ($user->getIsPublished() == 1) {
                $user->setIsPublished(0);
                $message = 'Artist page successfully unpublished!';
            } else {
                $user->setIsPublished(1);
                $message = 'Artist page successfully published!';
            }


            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', $message);
            return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
        }

        
        // ^ banner upload
        $formBanner = $this->createForm(BannerFormType::class);
        $formBanner->handleRequest($request);
    
        if ($formBanner->isSubmitted() && $formBanner->isValid()) {
            $bannerFile = $formBanner->get('picture')->getData();
            $newFilename = md5(uniqid(rand(), true)) . '.' . $bannerFile->guessExtension();
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

            if ($bannerFile) {
                
                // vérifier le format du fichier
                $oldBanner = $pictureRepo->findOneBy(['user' => $artistId, 'type' => 'banner']); 
                if (!in_array($bannerFile->getMimeType(), $allowedMimeTypes)) {
                    $this->addFlash('error', 'Wrong image format. Formats authorized: jpg, jpeg, png, webp');
                    return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
                }
        
                // Vérifier la taille du fichier
                $maxSize = 2 * 1024 * 1024; // 2 Mo
                if ($bannerFile->getSize() > $maxSize) {
                    $this->addFlash('error', 'Image is too heavy. Maximum size allowed: 2MB');
                    return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
                }

                
                if ($oldBanner) {
                    $oldBannerName = $oldBanner->getPath();
                    $bannerDirectory = 'images/artists/' . $artistId . '/banner';
                    $absoluteOldBannerPath = $this->getParameter('kernel.project_dir') . '/public/' . $bannerDirectory . '/' . $oldBannerName;
                    // dd($absoluteOldBannerPath);

                    $filesystem = new Filesystem();
                    if ($filesystem->exists($absoluteOldBannerPath)) {
                        $filesystem->remove($absoluteOldBannerPath);
                    }

                    $oldBanner->setPath($newFilename);

                } else {
                    // Si aucune bannière existante, créer le dossier "banner"
                    $filesystem = new Filesystem();
                    $filesystem->mkdir($bannerDirectory);

                    $picture = new Picture();

                    

                    $picture = $formBanner->getData();
                    $picture->setUser($artist);
                    $picture->setType('banner');
                    $picture->setPath($newFilename);
    
                    $entityManager->persist($picture);
                    
                }

            // Déplacer la nouvelle image vers le dossier "banner"
            

            $bannerFile->move($bannerDirectory, $newFilename);
            $entityManager->flush();

            }

            $this->addFlash('success', 'Your banner has been successfully added/edited');
            return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
        }


        // ^ pictures (gallery) upload
        $picturesGallery = $pictureRepo->findBy(['user' => $artistId, 'type' => 'work']); 

        $formPicture = $this->createForm(PictureFormType::class);      
        $formPicture->handleRequest($request);

        $maxImagesAllowed = 2;
        $numberOfImages = count($pictureRepo->findBy(['user' => $artistId, 'type' => 'work']));
        // vérifier si l'user peut upload une image, renvoie true ou false 
        $canUploadImage = $numberOfImages < $maxImagesAllowed;
        // dd($canUploadImage);

            // on définit le dossier de destination
            $folder = $artistId;
            
            if ($formPicture->isSubmitted() && $formPicture->isValid() && $numberOfImages < $maxImagesAllowed ) {
                $pictureFile = $formPicture->get('picture')->getData();
                 // on appelle le service d'ajout
                if ($pictureFile !== null) 
                {
                    $file = $pictureService->add($pictureFile, $folder, 300, 300);
                    $img = new Picture();
                    $img = $formPicture->getData();
                    $img->setPath($file);
                    $img->setType('work');
                    $img->setUser($artist);
                    $entityManager->persist($img);
                    $entityManager->flush();

                    $this->addFlash('success', 'Your picture has been successfully added to your gallery');
                    return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
                }

            } else {
                // $this->addFlash('error', 'Maximum image limit reached. Please delete some before adding more.');
                // return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
            }
           

        return $this->render('artist/manage_profil.html.twig', [
            'artist' => $artist,

            'formEditArtist'=> $form,
            'formPublishPageArtist' => $formPage,
            'formAddBanner' => $formBanner,
            'formAddPictureGallery' => $formPicture,
            'bannerExists' => $bannerExists,
            'picturesGallery' => $picturesGallery,
            'canUploadImage' => $canUploadImage,

            'instagram' => $instagram,
            'behance' => $behance,
            'facebook' => $facebook, 
            
        ]);
    }


    #[Route('/artist/{slug}/new', name: 'new_artist')]
    #[Route('/artist/{slug}/edit', name: 'edit_artist')]
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

            return $this->redirectToRoute('manage_profil', ['slug' => $user->getSlug()]);
        }
        return $this->redirectToRoute('manage_profil', ['slug' => $user->getSlug()]);
    }
}
