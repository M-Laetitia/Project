<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $googleId = '346078539825-0uda84aeorj9gb7jindava1pdct869qm.apps.googleusercontent.com';

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            'GOOGLE_ID' => $googleId,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // ^ auth google
    #[Route(path: '/connect', name: 'app_connect')]
    public function connect(Request $request): Response
    {
        $googleId = '346078539825-0uda84aeorj9gb7jindava1pdct869qm.apps.googleusercontent.com';
        $code = $request->query->get('code');

        return $this->render('security/connect.html.twig', [
            'GOOGLE_ID' => $googleId,
            'code' => $code,
        ]);
    }
}
