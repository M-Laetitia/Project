<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarType;
use App\Form\UserEditType;
use App\Form\ChangePasswordType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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



    #[Route('/user/{id}', name: 'show_user')]
    public function show(User $user = null, Security $security, Request $request, EntityManagerInterface $entityManager): Response {
    $user = $security->getUser();

    // Si l'utilisateur n'est pas connecté, redirection vers la page d'accueil ou une autre page.
    if (!$user instanceof User) {
        return $this->redirectToRoute('app_home');
    }

    $form = $this->createForm(AvatarType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {

                $oldAvatar = $user->getAvatar();
                if ($oldAvatar) {
                    $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$oldAvatar;
                    if (file_exists($oldAvatarPath)) {
                        $user->setAvatar(null);
                        $entityManager->persist($user);
                        $entityManager->flush();
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


        return $this->render('user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}/edit', name: 'edit_user')]
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


        $form2 = $this->createForm(ChangePasswordType::class);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            // Hasher le nouveau mot de passe et l'assigner à l'utilisateur
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData() 
            );
            $user->setPassword($hashedPassword);

            // Utiliser l'EntityManagerInterface injecté pour persister les changements
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la page de profil et afficher un message de succès
            $this->addFlash('success', 'Your password has been changed.');
            return $this->redirectToRoute('app_profile');
        }
        

        

        return $this->render('user/edit.html.twig', [
            'formEditUser' => $form,
            'ChangePasswordType' => $form2,
            // 'passwordForm' => $passwordForm,
            'edit' => $user->getId(),
           
        ]);

    }

    

}
