<?php

namespace App\Controller;


use App\Repository\UserRepository;
// use Geocoder\Query\GeocodeQuery;
// use Geocoder\Provider\Nominatim\Nominatim;
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
    public function index(UserRepository $userRepository): Response
    {

        $artists = $userRepository->findArtistUsers();

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

}
