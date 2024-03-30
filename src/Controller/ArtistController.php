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

    //^ get artist role
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

    //^ show artists list 
    #[Route('/artist', name: 'app_artist')]
    public function index(UserRepository $userRepository, Request $request, SessionInterface $session): Response
    {

        $artists = $userRepository->findAll(['isPublished' => 1]);
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
            'artists' => $artists, // Use all artists if no search result
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
    #[Route('/artist/{slug}', name: 'show_artist')]
    public function show(UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request, string $slug): Response {


        $artist = $userRepository->findOneBy(['slug' => $slug, 'isPublished' => 1]);
        // check if the artist exists
        if (!$artist) {
            // if not, redirect to the error page
            return $this->render('error/error404.html.twig', [], new Response('', Response::HTTP_NOT_FOUND));
        }



        $formPage = $this->createForm(PublishedArtistPageType::class);
        $formPage->handleRequest($request);


        if ($formPage->isSubmitted() && $formPage->isValid() ) {

            if ($this->isGranted('ROLE_ADMIN')) {
                if ($artist->getIsPublished() == 1) {
                    $artist->setIsPublished(-1);
                    $message = 'Artist page successfully moderated!';
                } else {
                    $artist->setIsPublished(1);
                    $message = 'Artist page successfully published!';
                }
    
                $entityManager->persist($artist);
                $entityManager->flush();
                $this->addFlash('success', $message);
                return $this->redirectToRoute('app_artist');

            } else {
                $this->addFlash('error', 'You do not have permission to censor this artist page.');
                return $this->redirectToRoute('app_artist');
            }
        }
        
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'formPublishPageArtist' => $formPage,

        ]);
    }

    // ^ artist page -manage  (ROLE ARTIST)
    #[Route('/role_artist/{slug}/manage', name: 'manage_artist')]
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


    // ^ artist page -manage  (ROLE ARTIST)  NEW !!!!
    #[Route('/role_artist/{slug}/artist_profile', name: 'manage_profil')]
    #[IsGranted("ROLE_ARTIST")]
    public function manageArtistProfil(User $user = null, Security $security, EntityManagerInterface $entityManager, ContactRepository $contactRepo , PictureRepository $pictureRepo, PictureService $pictureService,  Request $request): Response 
    {

        $artist = $security->getUser();
        $artistId = $artist->getId();
        $artistInfos = $user->getArtistInfos() ?? [];
        $artistSocials = $artist->getContacts();

        $bannerExists = null;
        $existingBanner = $pictureRepo->findOneBy(['user' => $artistId, 'type' => 'banner']); 
        if ($existingBanner) {
            $bannerExists =  $existingBanner->getPath();
        }
        
        $contactInstagram = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Instagram']);
        $contactBehance = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Behance']);
        $contactTwitter = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Twitter']);
        $contactDribbble = $contactRepo->findOneBy(['user' => $artistId, 'name' => 'Dribbble']);
      
        // Initialiser les variables avant la boucle
        $instagram = null;
        $behance = null;
        $twitter = null;
        $dribbble = null;

        $quote = null;
        $bio = null;
        $website = null;
        $shop = null;

        $country= null;
        $city= null;
        $street= null;
        $postalCode = null;

        foreach ($artistSocials as $social) {
            if ($social->getName() == 'Instagram') {
                $instagram = $social->getUrl();
            } elseif ($social->getName() == 'Behance') {
                $behance = $social->getUrl();
            } elseif ($social->getName() == 'Twitter') {
                $twitter = $social->getUrl();
            } elseif ($social->getName() == 'Dribbble') {
                $dribbble = $social->getUrl();
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
            $category =$form->get('category')->getData();
            
            $artistInstagram = $form->get('instagram')->getData();
            $artistBehance = $form->get('behance')->getData();
            $artistTwitter = $form->get('twitter')->getData();
            $artistDribbble = $form->get('dribbble')->getData();

            $bio = $form->get('bio')->getData();
            $quote = $form->get('quote')->getData();
            $website = $form->get('website')->getData();
            $shop = $form->get('shop')->getData();

            $country = $form->get('country')->getData();
            $street = $form->get('street')->getData();
            $city = $form->get('city')->getData();
            $postalCode = $form->get('postalCode')->getData();


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
            if ($category !== null && $category !== $artistInfos['category']) {
                $fields['category'] = $category;
            }

            //  vérifier d'abord si la clé "quote" est définie dans le tableau $artistInfos
            //  vérifier si la variable $quote est différente de null.
            //  vérifier si la valeur de $quote est différente de la valeur de "quote" dans $artistInfos. 
            if (isset($artistInfos['bio']) && $bio !== null && $bio !== $artistInfos['bio']) {
                $fields['bio'] = $bio;
            }
            if (isset($artistInfos['quote']) && $quote !== null && $quote !== $artistInfos['quote']) {
                $fields['quote'] = $quote;
            } else {
                $artistInfos['quote'] = $quote;
                $artist->setArtistInfos($artistInfos);
                $entityManager->persist($artist);
                $entityManager->flush();
            }

            if (isset($artistInfos['website']) && $website !== null && $website !== $artistInfos['website']) {
                $fields['website'] = $website;
            } else {
                // Ajouter le site web au tableau associatif
                $artistInfos['website'] = $website;
                $artist->setArtistInfos($artistInfos);
                $entityManager->persist($artist);
                $entityManager->flush();
            }

            if (isset($artistInfos['shop']) && $shop !== null && $shop !== $artistInfos['shop']) {
                $fields['shop'] = $shop;
            } else {
                // Ajouter le site web au tableau associatif
                $artistInfos['shop'] = $shop;
                $artist->setArtistInfos($artistInfos);
                $entityManager->persist($artist);
                $entityManager->flush();
            }


            if(isset($artistInfos['address'])) {
                    $address = $artistInfos['address'];
                if (isset($address['city']) && $city !== null && $city !== $address['city']) {
                    $fields['city'] = $city;
                } else {
                    // Le street n'existe pas encore dans l'adresse
                    $address['city'] = $city;
                }
                    $address['city'] = $city;
                    $artistInfos['address'] = $address;
                    $artist->setArtistInfos($artistInfos);
                    $entityManager->persist($artist);
                    $entityManager->flush();
                
            }

            if(isset($artistInfos['address'])) {
                $address = $artistInfos['address'];
                if (isset($address['country']) && $country !== null && $country !== $address['country']) {
                    $fields['country'] = $country;
                } else {
                    // Le street n'existe pas encore dans l'adresse
                    $address['country'] = $country;
                }
                    $address['country'] = $country;
                    $artistInfos['address'] = $address;
                    $artist->setArtistInfos($artistInfos);
                    $entityManager->persist($artist);
                    $entityManager->flush();
                
            }

            if(isset($artistInfos['address'])) {
                $address = $artistInfos['address'];
                if (isset($address['street']) && $street !== null && $street !== $address['street']) {
                    $address['street'] = $street;
                } else {
                    // Le street n'existe pas encore dans l'adresse
                    $address['street'] = $street;
                }
                    $address['street'] = $street;
                    $artistInfos['address'] = $address;
                    $artist->setArtistInfos($artistInfos);
                    $entityManager->persist($artist);
                    $entityManager->flush();
                
            }

            if(isset($artistInfos['address'])) {
                $address = $artistInfos['address'];
                if (isset($address['postalCode']) && $postalCode !== null && $postalCode !== $address['postalCode']) {
                    // Le postalCode existe déjà dans l'adresse et est différent
                    $address['postalCode'] = $postalCode;
                } else {
                    // Le postalCode n'existe pas encore dans l'adresse
                    $address['postalCode'] = $postalCode;
                }
            
                // Mettre à jour l'adresse dans le tableau global $artistInfos
                $artistInfos['address'] = $address;
            
                // Mettre à jour l'entité et persister les changements
                $artist->setArtistInfos($artistInfos);
                $entityManager->persist($artist);
                $entityManager->flush();
            }




           
            // ^ ADD / EDIT SOCIALS
            function createOrUpdateContact($entityManager, $artist, $name, $icon, $url, &$contactVariable, $artistVariable) {
                if (!$contactVariable) {
                    $contact = new Contact();
                    $contact->setUser($artist);
                    $contact->setName($name);
                    $contact->setIcon($icon);
                    $contact->setUrl($artistVariable);
                    $entityManager->persist($contact);
                } else {
                    $contact = $contactVariable;
                    $contact->setUrl($artistVariable);
                }
                $entityManager->flush($contact);
                }
                
            createOrUpdateContact($entityManager, $artist, "Twitter", '<i class="fa-brands fa-twitter"></i>', $artistTwitter, $contactTwitter, $artistTwitter);
            createOrUpdateContact($entityManager, $artist, "Dribbble", '<i class="fa-brands fa-dribbble"></i>', $artistDribbble, $contactDribbble, $artistDribbble);
            createOrUpdateContact($entityManager, $artist, "Behance", '<i class="fa-brands fa-square-behance"></i>', $artistBehance, $contactBehance, $artistBehance);
            createOrUpdateContact($entityManager, $artist, "Instagram", '<i class="fa-brands fa-instagram"></i>', $artistInstagram, $contactInstagram, $artistInstagram);
            // ^ __________________


            // Fusionner les champs avec artistInfos
            $artistInfos = array_merge($artistInfos, $fields);
            // Mettre à jour artistInfos dans l'entité User
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

                $newFilename = md5(uniqid(rand(), true)) . '.webp';
                // $newFilename = md5(uniqid(rand(), true)) . '.' . $bannerFile->guessExtension();
                if ($oldBanner) {
                    $oldBannerName = $oldBanner->getPath();
                    $bannerDirectory = 'images/artists/' . $artistId . '/banner';
                    $absoluteOldBannerPath = $this->getParameter('kernel.project_dir') . '/public/' . $bannerDirectory . '/' . $oldBannerName;

                    $filesystem = new Filesystem();
                    if ($filesystem->exists($absoluteOldBannerPath)) {
                        $filesystem->remove($absoluteOldBannerPath);
                    }
                    $oldBanner->setPath($newFilename);

                } else {
                    // Si aucune bannière existante, créer le dossier "banner"
                    $bannerDirectory = 'images/artists/' . $artistId . '/banner';
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

        // pictures (gallery) upload
        $picturesGallery = $pictureRepo->findBy(['user' => $artistId, 'type' => 'work']); 

        $formPicture = $this->createForm(PictureFormType::class);      
        $formPicture->handleRequest($request);

        $maxImagesAllowed = 12;
        $numberOfImages = count($pictureRepo->findBy(['user' => $artistId, 'type' => 'work']));
        // check if the suer car upload  an img or not , return true or false 
        $canUploadImage = $numberOfImages < $maxImagesAllowed;

            // we define the destination folder
            $folder = $artistId;
            
            if ($formPicture->isSubmitted() && $formPicture->isValid() && $numberOfImages < $maxImagesAllowed ) {
                $pictureFile = $formPicture->get('picture')->getData();
                 // we all the add picture service
                if ($pictureFile !== null) 
                {
                    $file = $pictureService->add($pictureFile, $folder, 500, 500);
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
            'twitter' => $twitter, 
            'dribbble' => $dribbble, 

            'maxImagesAllowed' => $maxImagesAllowed,
            
        ]);
    }

    
    // ^ Delete Picture
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

    // ^ Select Picture
    #[Route('/select-picture/{id}', name: 'select_picture', methods: ['GET'])]
    public function selectPicture(Security $security, Picture $picture, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        // Retrieve all images of the user/artist
        $pictures = $user->getPictures();

        
        //  Loop through these images
        foreach ($pictures as $pic) {
            // Update the selection status based on the selected image
            if ($pic->getId() === $picture->getId()) {
                $pic->setIsSelected(1);
            } else {
                $pic->setIsSelected(0);
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('manage_profil', ['slug' => $user->getSlug()]);

    }
}
