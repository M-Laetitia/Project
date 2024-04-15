<?php

namespace App\Controller;


use App\Entity\Area;
use App\Entity\Picture;
use App\Form\EventType;
use App\Form\PictureFormType;
use App\Service\PictureService;
use App\Repository\AreaRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AreaParticipationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    // ^ show list events (all)
    #[Route('/event', name: 'app_event')]
    public function index(AreaRepository $areaRepository): Response
    {

        
        $ongoingEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastEvents = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['ARCHIVED'],
        ]);

        $formattedEvents = []; // formater les events pour les rendre compatibles avec FullCalendar

        foreach ($ongoingEvents as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getName(),
                'start' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d H:i:s'),
                'slug' => $event->getSlug(),
            ];
        }
        // $response = new JsonResponse($formattedEvents);
        // dump($formattedEvents);die;

        return $this->render('event/index.html.twig', [
            'ongoingEvents' => $ongoingEvents,
            'pastEvents' => $pastEvents,
            'formattedEvents' => json_encode($formattedEvents), // Passer les données formatées en JSON à la vue
         
        ]);
    }

    // ^ Get events for calendar
    public function getEventsCalendar(AreaRepository $areaRepository): Response
    {
        $events = $areaRepository->findBy([
            'type' => 'EVENT',
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);

        $formattedEvents = []; // formater les events pour les rendre compatibles avec FullCalendar

        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'start' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d H:i:s'),
            ];
        }
        

        $jsonResponse = new JsonResponse($formattedEvents);
        dump($jsonResponse); die; 

        // Pour déboguer,  envoyer le contenu de la réponse directement dans le corps de la réponse HTTP
        // Cela peut être consulté dans l'onglet "Réseau" des outils de développement de votre navigateur
        // $jsonResponse->setContent(json_encode($formattedEvents));

        return $jsonResponse;
    }

    //^  new/edit event (admin)
    #[Route('/dashboard/event/new', name:'new_event', priority:1)]
    #[Route('/dashboard/event/edit/{id}', name:'edit_event', priority:1)]
    #[IsGranted("ROLE_ADMIN")]
    public function new_edit(Area $area = null, Request $request, PictureRepository $pictureRepo, PictureService $pictureService, EntityManagerInterface $entityManager ) : Response
    {
        $isNewEvent = !$area;
        if(!$area) {
            $area = new Area();
        }

        $bannerExists = null;
        $previewExists = null;
        $areaId = $area->getId();
        $form= $this->createForm(EventType::class, $area);
        $form->handleRequest($request);

        $formPicture = $this->createForm(PictureFormType::class);      
        $formPicture->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $area->setType('EVENT');
            $area = $form->getData();
            $area->setSlug($area->generateSlug());
            $entityManager->persist($area);
            $entityManager->flush();

            $areaId = $area->getId();
            $bannerDirectory = 'images/activity/event/' . $areaId . '/banner';

            // ^ BANNER IMAGE
            $bannerFile = $form->get('banner')->getData();
            $bannerTitle = $form->get('titleBanner')->getData();
            $bannerAlt = $form->get('altDescriptionBanner')->getData();
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            

            if ($bannerFile) {
                $oldBanner = $pictureRepo->findOneBy(['area' => $areaId, 'type' => 'banner']); 
                if (!in_array($bannerFile->getMimeType(), $allowedMimeTypes)) {
                    $this->addFlash('error', 'Wrong image format. Formats authorized: jpg, jpeg, png, webp');
                    return $this->redirectToRoute('new_event');
                }
                $maxSize = 2 * 1024 * 1024; // 2 Mo
                if ($bannerFile->getSize() > $maxSize) {
                    $this->addFlash('error', 'Image is too heavy. Maximum size allowed: 2MB');
                    return $this->redirectToRoute('new_event');
                }

                $newFilename = md5(uniqid(rand(), true)) . '.webp';
                if ($oldBanner) {
                    $oldBannerName = $oldBanner->getPath();
                    $bannerDirectory = 'images/activity/event/' . $areaId . '/banner';
                    $absoluteOldBannerPath = $this->getParameter('kernel.project_dir') . '/public/' . $bannerDirectory . '/' . $oldBannerName;

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
                $picture->setTitle($bannerTitle);
                $picture->setAltDescription($bannerAlt);
                $picture->setArea($area);
                $picture->setType('banner');
                $picture->setPath($newFilename);
                $entityManager->persist($picture);
                }
                
                // Déplacer la nouvelle image vers le dossier "banner"
                $bannerFile->move($bannerDirectory, $newFilename);
                $entityManager->flush();
            }

            // ^ PREVIEW IMAGE

            $previewFile = $form->get('preview')->getData();
            $previewTitle = $form->get('titlePreview')->getData();
            $previewAlt = $form->get('altDescriptionPreview')->getData();
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

            if ($previewFile) {
                $oldPreview = $pictureRepo->findOneBy(['area' => $areaId, 'type' => 'preview']); 
                if (!in_array($previewFile->getMimeType(), $allowedMimeTypes)) {
                    $this->addFlash('error', 'Wrong image format. Formats authorized: jpg, jpeg, png, webp');
                    return $this->redirectToRoute('show_event', ['slug' => $area->getSlug()]);
                }
                $maxSize = 2 * 1024 * 1024; // 2 Mo
                if ($previewFile->getSize() > $maxSize) {
                    $this->addFlash('error', 'Image is too heavy. Maximum size allowed: 2MB');
                    return $this->redirectToRoute('new_event');
                }

                $newFilename = md5(uniqid(rand(), true)) . '.webp';
                $areaId = $area->getId();

                if ($oldPreview) {
                    $oldPreviewName = $oldPreview->getPath();
                    $previewDirectory = 'images/activity/event/' . $areaId . '/banner';
                    $absoluteOldPreviewPath = $this->getParameter('kernel.project_dir') . '/public/' . $previewDirectory . '/' . $oldPreviewName;

                    $filesystem = new Filesystem();
                    if ($filesystem->exists($absoluteOldPreviewPath)) {
                        $filesystem->remove($absoluteOldPreviewPath);
                    }
                    $oldPreview->setPath($newFilename);

                } else {


                $filesystem = new Filesystem();
                $filesystem->mkdir($bannerDirectory);

                $picture = new Picture();
                $picture->setTitle($previewTitle);
                $picture->setAltDescription($previewAlt);
                $picture->setArea($area);
                $picture->setType('preview');
                $picture->setPath($newFilename);
                $entityManager->persist($picture);
                }
                
                // Déplacer la nouvelle image vers le dossier "banner"
                $previewFile->move($bannerDirectory, $newFilename);
                $entityManager->flush();
            }

          

            $message = $isNewEvent ? 'Event created successfully!' : 'Event edited successfully!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('show_event', ['slug' => $area->getSlug()]);
        }


          // ^ GALLERY IMAGES
          $picturesGallery = $pictureRepo->findBy(['area' => $areaId, 'type' => 'picture']); 
          $maxImagesAllowed = 12;
          $numberOfImages = count($pictureRepo->findBy(['area' => $areaId, 'type' => 'picture']));
          // check if the suer car upload  an img or not , return true or false 
          $canUploadImage = $numberOfImages < $maxImagesAllowed;

          $folder = $area->getName();
          
          
          if ($formPicture->isSubmitted() && $formPicture->isValid() && $numberOfImages < $maxImagesAllowed ) {
              $pictureFile = $formPicture->get('picture')->getData();
               // on appelle le service d'ajout
              if ($pictureFile !== null) 
              {
                  $file = $pictureService->add($pictureFile, $folder, 500, 500);
                  $img = new Picture();
                  $img = $formPicture->getData();
                  $img->setPath($file);
                  $img->setType('picture');
                  $img->setArea($area);
                  $entityManager->persist($img);
                  $entityManager->flush();   
                  
                  $this->addFlash('success', 'Your picture has been successfully added');
                  return $this->redirectToRoute('show_event', ['slug' => $area->getSlug()]);
              }           
          

            } else {
                // $this->addFlash('error', 'Maximum image limit reached. Please delete some before adding more.');
                // return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
            }


        return $this->render('dashboard/newEvent.html.twig', [
            'formAddEvent' => $form,
            'edit' =>$area->getId(),
            'maxImagesAllowed' => $maxImagesAllowed,
            'canUploadImage' => $canUploadImage,
            'formAddPictureGallery' => $formPicture,
            'picturesGallery' => $picturesGallery,
            'area' =>$area,

            'bannerExists' => $bannerExists,
            'previewExists' => $previewExists,
        ]);
    }

    // ^ show event (admin)
    #[Route('/dashboard/event/{slug}', name: 'show_event_admin')]
    #[IsGranted("ROLE_ADMIN")]
    public function show_admin(Area $area = null, AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
        
        return $this->render('dashboard/showEvent.html.twig', [
            'area' => $area,
        ]);
    }
    

    
    // & show event
    // Définit une route pour afficher un événement en utilisant son slug
    #[Route('archived/event/{slug}', name: 'show_archived_event')]
    #[Route('/event/{slug}', name: 'show_event')]
    public function show(Area $area = null, User $user = null, AreaRepository $areaRepository,  AreaParticipationRepository $areaParticipationRepository, Security $security): Response 
    {
        // Récupérer l'objet Area (événement) en fonction du slug passé en paramètre
        $area = $areaRepository->findOneBy(['slug' => $area->getslug()]);
        // vérifier si l'area (ici un event) existe 
        if (!$area) {
            // Si non, rediriger vers la page d'erreur
            return $this->render('error/error404.html.twig', [], new Response('', Response::HTTP_NOT_FOUND));
        }
        $areaId = $area->getId();
        // Vérifier si un utilisateur est connecté
        if($user = $security->getUser()) {
            $userId = $user->getId();
            $existingParticipation = [];
            // Vérifier si l'utilisateur a déjà une participation à cet événement
            $hasExistingParticipation = $areaParticipationRepository->findOneBy(['user' => $user->getId(), 'area' => $areaId]);
            $existingParticipation = $hasExistingParticipation !== null;

            // Retourner la vue de l'événement avec l'information sur la participation
            return $this->render('event/show.html.twig', [
                'area' => $area,
                'existingParticipation' => $existingParticipation
            ]);
        }
        // Si aucun utilisateur n'est connecté, simplement retourner la vue de l'événement
        return $this->render('event/show.html.twig', [
            'area' => $area,
        ]);
    }


    // ^ Delete Event (admin)
    #[Route('/dashboard/event/{slug}/delete', name: 'delete_event')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete_event(Area $area, EntityManagerInterface $entityManager) :Response 
    {
        $entityManager->remove($area);
        $entityManager->flush();
        
        $this->addFlash('success', 'The event has been successfully deleted');
        return $this->redirectToRoute('app_dashboard');
    }

    // ^ Delete Picture
    #[Route('/delete/picture/event/{id}', name: 'delete_event_picture')]
    public function deletePictureEvent( Security $security, Picture $picture, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService ): Response
    {
       
        $areaId = $picture->getArea()->getId();
        $name = $picture->getPath();
        

        if($pictureService->delete($name, $areaId , 500, 500)) {
            //on supprime l'image de la base données
            $entityManager->remove($picture);
            $entityManager->flush();


            $this->addFlash('success', 'Image deleted successfully.'); // Message flash de succès

            return $this->redirectToRoute('edit_event', ['id' => $areaId]);
        }
        return $this->redirectToRoute('edit_event', ['id' => $areaId]);
    }


    // ^ AJAX - all archived events
    #[Route('/all-past-events', name: 'all-past-events', methods: ['POST'])]
    public function getPastEvents(Request $request, AreaRepository $areaRepository)
    {
        $pastEvents = $areaRepository->findBy(
            [
                'type' => 'EVENT',
                'status' => ['ARCHIVED'],
            ],
            ['startDate' => 'DESC'] 
        );
        // dd($pastEvents);

        // Convert objects to associative arrays
        $eventsArray = [];
        foreach ($pastEvents as $event) {
            $formattedDate = $event->getStartDate()->format('d-m-Y H:i');
            $eventsArray[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'slug' => $event->getSlug(),
                'date' => $formattedDate,
            ];
        }

        // Convert associative array to JSON and send response
        return new JsonResponse($eventsArray);
    }
    
    // ^ AJAX - all archived content
    #[Route('/all-past-content', name: 'all-past-content', methods: ['POST'])]
    public function getPastContent(Request $request, AreaRepository $areaRepository)
    {
        $pastContent = $areaRepository->getAllPastContent();
        
        // Convert objects to associative arrays
        $pastContentArray = [];
        foreach ($pastContent as $content) {
            $formattedDate = $content->getStartDate()->format('d-m-Y H:i');
            $pastContentArray[] = [
                'id' => $content->getId(),
                'name' => $content->getName(),
                'slug' => $content->getSlug(),
                'date' => $formattedDate,
            ];
        }

        // Convert associative array to JSON and send response
        return new JsonResponse($pastContentArray);
    }
}
