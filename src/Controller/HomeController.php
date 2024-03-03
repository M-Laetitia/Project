<?php

namespace App\Controller;



// use Geocoder\Query\GeocodeQuery;
// use Geocoder\Provider\Nominatim\Nominatim;
use App\Form\ArtistStatusType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    // private function getLatLngFromAddress(string $address): array
    // {
    //     // Utilize the geocoding function to obtain coordinates from the address
    //     $geocoder = new Nominatim(new Client());

    //     $result = $geocoder->geocodeQuery(GeocodeQuery::create($address));

    //     if ($result->count() > 0) {
    //         $latlng = $result->first()->getCoordinates();
    //         return [$latlng->getLatitude(), $latlng->getLongitude()];
    //     }

    //     return [0, 0]; // Error handling, default coordinates
    // }

    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $security->getUser();
        $artists = $userRepository->findArtistUsers();

        $form = $this->createForm(ArtistStatusType::class, $user);
        $form->handleRequest($request);

        if ($user) {
            // Récupérer les rôles de l'utilisateur
            $userRoles = $user->getRoles();
        }    

        if ($form->isSubmitted() && $form->isValid() ) {
            // Ajouter le rôle "ROLE_ARTIST" si ce n'est pas déjà présent
            if (!in_array('ROLE_ARTIST', $userRoles, true)) {
                $userRoles[] = 'ROLE_ARTIST';
            }

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

            // Mettez à jour artistInfos dans l'entité User
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

            return $this->redirectToRoute('app_home');
        }




        // $markers = [];

        // foreach ($artists as $artist) {
        //     $address = sprintf(
        //         '%s, %s, %s',
        //         $artist->getArtistInfos()['address']['street'],
        //         $artist->getArtistInfos()['address']['city'],
        //         $artist->getArtistInfos()['address']['postalCode']
        //     );

        //     $markers[] = [
        //         'address' => $address,
        //         'latlng' => $this->getLatLngFromAddress($address),
        //     ];
        // }



        return $this->render('home/index.html.twig', [
            'artists' => $artists,
            // 'markers' => $markers,
            'formStatusArtist'=> $form,
        ]);
    }

    #[Route('/homepage', name: 'app_homepage')]
    public function homePage(UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $security->getUser();
        return $this->render('home/homePage.html.twig', [

        ]);
    }

    #[Route('/landingpage', name: 'app_langindPage')]
    public function landingPage(UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $security->getUser();
        return $this->render('home/landingPage.html.twig', [

        ]);
    }

      // ^ test stripe
      #[Route('/home/test_stripe', name: 'test_stripe')]
      public function index_stripe(): Response
      {

          return $this->render('home/stripeTest.html.twig', [
             
          ]);
      }

      // ^ test stripe payment php
      #[Route('/home/payment', name: 'test_payment')]
      public function index_payment(): Response
      {

          return $this->render('home/payment.php', [
             
          ]);
      }

      // ^ test style
      #[Route('/home/style', name: 'test_style')]
      public function index_style(): Response
      {

          return $this->render('home/testStyle.html.twig', [
             
          ]);
      }

      


}
