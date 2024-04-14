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


        return $this->render('home/home.html.twig', [
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

    // ^ test style
    #[Route('/home/style', name: 'test_style')]
    public function index_style(): Response
    {

        return $this->render('home/testStyle.html.twig', [
            
        ]);
    }

    // ^ contact
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {

        return $this->render('home/contact.html.twig', [
        ]);
    }

    // ^ Privacy policy
    #[Route('/privacy-policy', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {

        return $this->render('home/privacyPolicy.html.twig', [
        ]);
    }

    // ^ Terms of Use
    #[Route('/terms-of-use', name: 'app_terms_of_use')]
    public function termsOfUse(): Response
    {

        return $this->render('home/termsOfUse.html.twig', [
        ]);
    }

    // ^ Sitemap
    #[Route('/sitemap', name: 'app_sitemap')]
    public function sitemap(): Response
    {

        // Render the contents of 'home/sitemap.xml.twig' into a string
        $content = $this->renderView('home/sitemap.xml.twig');

        // Create a new Response object with the rendered content
        $response = new Response($content);
        // Set the Content-Type header of the response to 'application/xml'
        $response->headers->set('Content-Type', 'application/xml');

        // Return the response with the rendered XML content
        return $response;
    }


      


}
