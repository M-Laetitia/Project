<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Form\PictureFormType;
use App\Repository\UserRepository;
use App\Repository\PictureRepository;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WorkshopRegistrationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkshopController extends AbstractController
{
    #[Route('/workshop', name: 'app_workshop')]
    public function index(WorkshopRepository $workshopRepository): Response
    {

        $workshops = $workshopRepository->findBy([]);

        $ongoingWorkshop = $workshopRepository->findBy([
            'status' => ['OPEN', 'PENDING', 'CLOSED'],
        ]);
        $pastWorkshop = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ]);



        return $this->render('workshop/index.html.twig', [
            'workshops' => $workshops,
            'ongoingWorkshop' => $ongoingWorkshop,
            'pastWorkshop' => $pastWorkshop,
        ]);
       
    }

    // ^ show workshop (user)
    #[Route('/workshop/{slug}', name: 'show_workshop')]
    public function show(Workshop $workshop = null, Security $security, WorkshopRegistrationRepository $workshopRegistrationRepository ): Response
    {

        $workshopId = $workshop->getId();
        if($user = $security->getUser()) {
            $user = $security->getUser();
            $userId = $user->getId();
            $existingRegistration = [];
            $hasExistingRegistration = $workshopRegistrationRepository->findOneBy(['user' => $user->getId(), 'workshop' => $workshopId]);
            $existingRegistration = $hasExistingRegistration !== null;

            return $this->render('workshop/show.html.twig', [
                'workshop' => $workshop,
                'existingRegistration' => $existingRegistration,
                'user' => $user,
            ]);
        }
       

        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }

    // ^ Create/Edit workshop (admin)
    #[Route('/dashboard/workshop/new/', name:'new_workshop' , priority:1)]
    #[Route('/dashboard/workshop/{slug}/edit', name:'edit_workshop')]
    public function new_edit(Workshop $workshop = null, Request $request, UserRepository $userRepository, PictureRepository $pictureRepo, EntityManagerInterface $entityManager ) : Response
    {

        // Fetch users with the specified role
        $users = $userRepository->findUsersbyRole("ROLE_SUPERVISOR");
        
        // dump($users);die;
        $isNewEvent = !$workshop;
        if(!$workshop) {
            $workshop = new Workshop();
        }

        $maxImagesAllowed = 12;
        $workshopId = $workshop->getId();
        $numberOfImages = count($pictureRepo->findBy(['workshop' => $workshopId, 'type' => 'picture']));
        $canUploadImage = $numberOfImages < $maxImagesAllowed;


        $bannerExists = null;
        $existingBanner = $pictureRepo->findOneBy(['workshop' => $workshopId, 'type' => 'banner']); 
        if ($existingBanner) {
            $bannerExists =  $existingBanner->getPath();
        }

        $previewExists = null;
        $existingPreview = $pictureRepo->findOneBy(['workshop' => $workshopId, 'type' => 'preview']); 
        if ($existingPreview) {
            $previewExists =  $existingPreview->getPath();
        }

        $form = $this->createForm(WorkshopType::class, $workshop, [
            'users' => $users,
        ]);

        $form->handleRequest($request);

        $formPicture = $this->createForm(PictureFormType::class);      
        $formPicture->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            

            $workshop->setStatus('OPEN');
            $workshop = $form->getData();
            $workshop->setSlug($workshop->generateSlug());
            $entityManager->persist($workshop);
            $entityManager->flush();

            $workshopId = $workshop->getId();
            $bannerDirectory = 'images/activity/workshop/' . $workshopId . '/banner';


            // ^ BANNER IMAGE
            $bannerFile = $form->get('banner')->getData();
            $bannerTitle = $form->get('titleBanner')->getData();
            $bannerAlt = $form->get('altDescriptionBanner')->getData();
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            

            if ($bannerFile) {
                $oldBanner = $pictureRepo->findOneBy(['workshop' => $workshopId, 'type' => 'banner']); 
                if (!in_array($bannerFile->getMimeType(), $allowedMimeTypes)) {
                    $this->addFlash('error', 'Wrong image format. Formats authorized: jpg, jpeg, png, webp');
                    return $this->redirectToRoute('new_workshop');
                }
                $maxSize = 2 * 1024 * 1024; // 2 Mo
                if ($bannerFile->getSize() > $maxSize) {
                    $this->addFlash('error', 'Image is too heavy. Maximum size allowed: 2MB');
                    return $this->redirectToRoute('new_workshop');
                }

                $newFilename = md5(uniqid(rand(), true)) . '.webp';
                if ($oldBanner) {
                    $oldBannerName = $oldBanner->getPath();
                    $bannerDirectory = 'images/activity/workshop/' . $workshopId . '/banner';
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
                $picture->setWorkshop($workshop);
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
                $oldPreview = $pictureRepo->findOneBy(['workshop' => $workshopId, 'type' => 'preview']); 
                if (!in_array($previewFile->getMimeType(), $allowedMimeTypes)) {
                    $this->addFlash('error', 'Wrong image format. Formats authorized: jpg, jpeg, png, webp');
                    return $this->redirectToRoute('edit_workshop', ['slug' => $workshop->getSlug()]);
                }
                $maxSize = 2 * 1024 * 1024; // 2 Mo
                if ($previewFile->getSize() > $maxSize) {
                    $this->addFlash('error', 'Image is too heavy. Maximum size allowed: 2MB');
                    return $this->redirectToRoute('new_workshop');
                }

                $newFilename = md5(uniqid(rand(), true)) . '.webp';
                $workshopId = $workshop->getId();

                if ($oldPreview) {
                    $oldPreviewName = $oldPreview->getPath();
                    $previewDirectory = 'images/activity/workshop/' . $workshopId . '/banner';
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
                $picture->setWorkshop($workshop);
                $picture->setType('preview');
                $picture->setPath($newFilename);
                $entityManager->persist($picture);
                }
                
                // Déplacer la nouvelle image vers le dossier "banner"
                $previewFile->move($bannerDirectory, $newFilename);
                $entityManager->flush();
            }

          

            $message = $isNewEvent ? 'Workshop created successfully!' : 'Workshop edited successfully!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('edit_workshop', ['slug' => $workshop->getSlug()]);
        }

          // ^ GALLERY IMAGES
          $picturesGallery = $pictureRepo->findBy(['workshop' => $workshopId, 'type' => 'picture']); 

          $folder = $workshop->getName();
          
          
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
                  $img->setWorkshop($workshop);
                  $entityManager->persist($img);
                  $entityManager->flush();   
                  
                  $this->addFlash('success', 'Your picture has been successfully added');
                  return $this->redirectToRoute('edit_workshop', ['slug' => $workshop->getSlug()]);
              }           
          

            } else {
                // $this->addFlash('error', 'Maximum image limit reached. Please delete some before adding more.');
                // return $this->redirectToRoute('manage_profil', ['slug' => $artist->getSlug()]);
            }


    
        return $this->render('dashboard/newWorkshop.html.twig', [
            'form' => $form,
            'edit' =>$workshop->getId(),
            'workshopId' => $workshop->getId(),

            'maxImagesAllowed' => $maxImagesAllowed,
            'canUploadImage' => $canUploadImage,
            'formAddPictureGallery' => $formPicture,
            'picturesGallery' => $picturesGallery,

            'bannerExists' => $bannerExists,
            'previewExists' => $previewExists,
           
        ]);
    }


    // ^ show workshop (admin)
    #[Route('/dashboard/workshop/{slug}', name: 'show_workshop_admin')]
    public function show_admin(Workshop $workshop = null): Response
    {

        return $this->render('dashboard/showWorkshop.html.twig', [
            'workshop' => $workshop,
        ]);
    }
   
    // ^ Delete workshop (admin)
    #[Route('/dashboard/workshop/{slug}/delete', name:'delete_workshop')] 
    public function delete(Workshop $workshop, EntityManagerInterface $entityManager) :Response
    {

        $area = $workshop->getArea(); 
        // dump($workshop);die;
        $entityManager->remove($workshop);
        $entityManager->flush();


        $nbReversationRemaining = $area->getNbReversationRemaining();
        if ( $nbReversationRemaining == 0 && $area->getStatus() !== 'CLOSED') {
            // Update the status to "closed"
            $area->setStatus('CLOSED');
            $entityManager->flush();
        } elseif ($nbReversationRemaining > 0 && $area->getStatus() !== 'OPEN') {
            $area->setStatus('OPEN');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard');
    }

    // ^ AJAX - all archived workshops
    #[Route('/all-past-workshops', name: 'all-past-workshops', methods: ['POST'])]
    public function getPastWorkshops(Request $request, WorkshopRepository $workshopRepository)
    {

        $pastWorkshops = $workshopRepository->findBy([
            'status' => ['ARCHIVED'],
        ],
        ['startDate' => 'DESC'] 
        );

        // Convert objects to associative arrays
        $workshopsArray = [];
        foreach ($pastWorkshops as $workshop) {
            $workshopsArray[] = [
                'id' => $workshop->getId(),
                'name' => $workshop->getName(),
                'slug' => $workshop->getSlug(),
            ];
        }

        // Convert associative array to JSON and send response
        return new JsonResponse($workshopsArray);
    }

}



// Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Feugiat in fermentum posuere urna nec tincidunt. Sit amet mauris commodo quis imperdiet massa