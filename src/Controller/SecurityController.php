<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use League\OAuth2\Client\Provider\Google;

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
    public function connect(Request $request): Response
    {
        $googleId = '346078539825-0uda84aeorj9gb7jindava1pdct869qm.apps.googleusercontent.com';
        $code = $request->query->get('code');

        // Utilisez le code pour obtenir un jeton d'accès OAuth 2.0
        $provider = new Google([
            'clientId'     => $googleId,
            'clientSecret' => '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',
            'redirectUri'  => 'http://127.0.0.1:8000/connect',
        ]);

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        // Utilisez le jeton d'accès pour authentifier la requête à l'API Google
        $client = new Client([
            'timeout'  => 2.0,
            'headers' => [
                'Authorization' => 'Bearer ' . $token->getToken(),
            ],
            'verify' => $projectRoot.'config/cacert.pem',
        ]);

        // Utilisez le client pour effectuer une requête à l'API Google
        $response = $client->request('GET', 'https://openidconnect.googleapis.com/v1/userinfo');
        // dump((string)$response->getBody());die;

        return $this->render('security/connect.html.twig', [
            'GOOGLE_ID' => $googleId,
            'code' => $code,
        ]);
    }
}

