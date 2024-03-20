<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarType;
use App\Form\UserEditType;
use App\DTO\ChangePasswordModel;
use App\Form\ChangePasswordType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    // ^ SHOW USER
    #[Route('/profile/user/{slug}', name: 'show_user')]
    #[IsGranted("ROLE_USER")]
    public function show(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager): Response {

    if(!$user) {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }

    $user = $security->getUser();

    // Si l'utilisateur n'est pas connecté, redirection vers la page d'accueil ou une autre page.
    // if (!$user instanceof User) {
    //     return $this->redirectToRoute('app_home');
    // }

    // ^ edit avatar
    $form = $this->createForm(AvatarType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {

                $oldAvatar = $user->getAvatar();
                if ($oldAvatar) {
                    $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$oldAvatar;
                    $newPath = $this->getParameter('oldPictures_directory').'/'.$oldAvatar;

                    if (file_exists($oldAvatarPath)) {
                        rename($oldAvatarPath, $newPath);
                    }
                }

                $newFilename = uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $user->setAvatar($newFilename);
                $entityManager->flush();
            }

            return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
        }


        // ^ edit infos
        $formInfos = $this->createForm(UserEditType::class, $user);
        $formInfos->handleRequest($request);
        if ($formInfos->isSubmitted() && $formInfos->isValid()) {

            $user = $formInfos->getData();
            // $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated.');
            return $this->redirectToRoute('show_user', ['slug' => $user->getSlug()]);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formEditUser' => $formInfos, 
        ]);
    }


    // ^ EDIT USER
    #[Route('/profile/user/{id}/edit', name: 'edit_user')]
    #[IsGranted("ROLE_USER")]
    public function new_edit(User $user = null, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) : Response
    {

        // Check if the user is connected
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }


        // FORM TO EDIT USERNAME, EMAIL, AVATAR
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // // edit avatar ------------------------------
            // $avatarFile = $form->get('avatar')->getData();
            // if ($avatarFile) {

            //     $oldAvatar = $user->getAvatar();
            //     if ($oldAvatar) {
            //         $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$oldAvatar;
            //         if (file_exists($oldAvatarPath)) {
            //             $user->setAvatar(null);
            //             $entityManager->persist($user);
            //             $entityManager->flush();
            //         }
            //     }

            //     $newFilename = uniqid().'.'.$avatarFile->guessExtension();

            //     try {
            //         $avatarFile->move(
            //             $this->getParameter('avatars_directory'),
            //             $newFilename
            //         );
            //     } catch (FileException $e) {
            //     }
            //     $user->setAvatar($newFilename);
            //     $entityManager->flush();
            // }
            // // end edit avatar ------------------------

            $user = $form->getData();
            // $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated.');
            return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
        }


        return $this->render('user/edit.html.twig', [
            'formEditUser' => $form,
            'edit' => $user->getId(),
           
        ]);

    }

    // ^ EDIT PASSWORD USER
    #[Route('/profile/user/{id}/editPassword', name: 'editPassword_user')]
    #[IsGranted("ROLE_USER")]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // check that the user is logged in
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Create and instand of DTO (Data Transfer Object) to handle the password modification
        $changePasswordModel = new ChangePasswordModel();

       
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $changePasswordModel->getNewPassword() // use DTO
            );
            $user->setPassword($hashedPassword);

            // persist and flush the new data (password) in user
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your password has been changed.');
            return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
        }
        return $this->render('user/editPassword.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }

    // ^ DELETE USER

    #[Route('/admin/user/{id}/delete', name: 'delete_user_admin')]
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/profile/user/{id}/delete', name: 'delete_user')]
    #[IsGranted("ROLE_USER")]
    public function delete(User $user, EntityManagerInterface $entityManager, Security $security, SessionInterface $session) : Response {

        $user = $security->getUser();

        // Anonymisation for subscriptions 
        $subscriptions = $user->getSubscriptions();
        foreach ($subscriptions as $subscription) {
            $subscription->setUser(null); 
            $entityManager->persist($subscription);
        }

        // Anonymisation for participations (areaParticipation)
        $participations = $user->getAreaParticipations();
        foreach ($participations as $participation) {
            $participation->setUser(null); 
            $entityManager->persist($participation);
        }

        // Anonymisation for registration (workshopRegistration)
        $registrations = $user->getWorkshopRegistrations();
        foreach ($registrations as $registration) {
            $registration->setUser(null); 
            $entityManager->persist($registration);
        }

        // Anonymisation for exposition proposals (role artist) (expositionProposals)
        $proposals = $user->getExpositionProposals();
        foreach ($proposals as $proposal) {
            $proposal->setUser(null); 
            $entityManager->persist($proposal);
        }

        // Anonymisation for workshops (role supervisor) (workshop)
        $workshops = $user->getWorkshops();
        foreach ($workshops as $workshop) {
            $workshop->setUser(null); 
            $entityManager->persist($workshop);
        }

        // Anonymisation for timeslots (role supervisor) (timeslots)
        $timeslots = $user->getTimeslots();
        foreach ($timeslots as $timeslot) {
            $timeslot->setUser(null); 
            $entityManager->persist($timeslot);
        }

        // Deletion of user images (role artist) (picture)
        $images = $user->getPictures();
        foreach ($images as $image) {
            $entityManager->remove($image);
        }

        // Deletion of user contacts (role artist) (contact)
        $contacts = $user->getContacts();
        foreach ($contacts as $contact) {
            $entityManager->remove($contact);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        // logout
        $security->invalidateSession();

        // $session = $request->getSession();
        // $session = new Session();
        // $session->invalidate();

        return $this->redirectToRoute('app_home');
    }
}


