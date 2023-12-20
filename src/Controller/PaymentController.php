<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Entity\Subscription;
use App\Entity\SubscriptionType;
use App\Form\SubscriptionPaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\SubscriptionTypeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{

    // ^ show subscriptions
    #[Route('/payment', name: 'app_subscription')]
    public function index(SubscriptionTypeRepository $subscriptionTypeRepository): Response
    {

        $subscriptions = $subscriptionTypeRepository->findBy([]);

        return $this->render('payment/subscriptions.html.twig', [
            'subscriptions' => $subscriptions
        ]);
    }

    // // ^ payment page
    // #[Route('/payment/{id}', name: 'subscription_payment')]
    // public function index_payment(SubscriptionType $subscriptionType = null, Subscription $subscription = null, Request $request, Security $security, EntityManagerInterface $entityManager ): Response
    // {

    //     $user = $security->getUser();
    //     // ! à revoir 
    //     // Si $subscriptionType est null, essayez de charger le SubscriptionType depuis la base de données en utilisant l'ID
    //     if (!$subscriptionType && $request->attributes->has('id')) {
    //         $subscriptionTypeId = $request->attributes->get('id');
    //         $subscriptionType = $this->getDoctrine()->getRepository(SubscriptionType::class)->find($subscriptionTypeId);
    //     }


    //     $form = $this->createForm(SubscriptionPaymentType::class , $subscription);
    //     $form->handleRequest($request); 

    //     $clientSecret = null;


    //     if ($form->isSubmitted() && $form->isValid() ) {
    //         // ^Json infos
            
            
    //         // récupérer montant subscription
    //         $total = $subscriptionType->getPrice();

    //         // STRIPE:
    //         require_once('../vendor/autoload.php');
    //         Stripe::setApiKey('sk_test_51OOfxmFInhPlxmzG0BuQ347vV5XipJaK5kaF3QWlN7GFwdJE78EtYLCQve2pT7BeqE0VoxX9qjvn6hi87wYry67B00g9GKqSln');

    //         $intent = PaymentIntent::create([
    //         'amount' => $total*100,
    //         'currency' => 'eur',
    //         ]);

    //         $clientSecret = $intent['client_secret'];
    //         //  dump($clientSecret);die;
    //         //  pi_3OPA9KFInhPlxmzG0ZTvlRuS_secret_0JQRaXRVvKRLtMnfSc5h8CXZs
            
            
    //         // Récupérer les valeurs pour le champ infosUser (json)
    //         $firstname = $form->get('firstname')->getData();
    //         $lastname =$form->get('lastname')->getData();
    //         $address =$form->get('address')->getData();
            
      
    
    //         // Définir les champs et leurs valeurs
    //         $fields = [
    //            'firstname' => $firstname,
    //            'lastname' => $lastname,
    //            'address' => $address,
    //        ];

    //        $name = $subscriptionType->getName();
    //        $price = $subscriptionType->getPrice();
    //        $duration = $subscriptionType->getDuration();

    //        $subscriptionInfo = [
    //         'name' => $name,
    //         'price' => $price,
    //         'duration' => $duration,
    //         ];


    //        // remplir les autres champs:
    //        $subscription = new Subscription();
    //         //    $subscription = $form->getData();
    //        $subscription->setUser($user);
    //        $subscription->setInfosUser($fields);
    //        $subscription->setPaymentDate(new \DateTimeImmutable());
    //        $subscription->setInfosSubscription($subscriptionInfo);
    //        $subscription->setSubscriptionType($subscriptionType);
    //        $subscription->setTotal($total);



    //        $entityManager->persist($subscription);
    //        $entityManager->flush();

    //         $this->addFlash('success', 'success');
    //         return $this->redirectToRoute('app_home');
    //         dump($intent);die;
    //     }


        
    //     return $this->render('payment/payment.html.twig', [
    //         'subscriptionType' => $subscriptionType,
    //         'formSubscriptionPayment' => $form,
    //         'clientSecret' => $clientSecret,
    
    //     ]);
    // }
    
     // ^ payment page
     #[Route('/payment/{id}', name: 'subscription_payment', methods: ['GET','POST'])]
     public function index_payment(SubscriptionType $subscriptionType = null, Subscription $subscription = null, Request $request, Security $security, EntityManagerInterface $entityManager ): Response
     {
 
         $user = $security->getUser();

         // Obtenir la clé secrète de Stripe
        // Stripe::setApiKey($this->stripeSecretKey);

         // ! à revoir 
         // Si $subscriptionType est null, essayez de charger le SubscriptionType depuis la base de données en utilisant l'ID
         if (!$subscriptionType && $request->attributes->has('id')) {
             $subscriptionTypeId = $request->attributes->get('id');
             $subscriptionType = $this->getDoctrine()->getRepository(SubscriptionType::class)->find($subscriptionTypeId);
         }
        // !
 
        //  $form = $this->createForm(SubscriptionPaymentType::class , $subscription);
        //  $form->handleRequest($request); 
 
         $clientSecret = null;
 
 
         
             // ^Json infos
             
             
             // récupérer montant subscription
             $total = $subscriptionType->getPrice();
 
             // STRIPE:
             require_once('../vendor/autoload.php');
             Stripe::setApiKey('sk_test_51OOfxmFInhPlxmzG0BuQ347vV5XipJaK5kaF3QWlN7GFwdJE78EtYLCQve2pT7BeqE0VoxX9qjvn6hi87wYry67B00g9GKqSln');
 
             $intent = PaymentIntent::create([
             'amount' => $total*100,
             'currency' => 'eur',
             ]);
 
             $clientSecret = $intent['client_secret'];
             //  dump($clientSecret);die;
             //  pi_3OPA9KFInhPlxmzG0ZTvlRuS_secret_0JQRaXRVvKRLtMnfSc5h8CXZs
             
             
             // Récupérer les valeurs pour le champ infosUser (json)
             //récupérer données form
             $firstname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $address = $request->request->get('address');
             
       
     
             // Définir les champs et leurs valeurs
             $fields = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'address' => $address,
            ];
 
            $name = $subscriptionType->getName();
            $price = $subscriptionType->getPrice();
            $duration = $subscriptionType->getDuration();
 
            $subscriptionInfo = [
             'name' => $name,
             'price' => $price,
             'duration' => $duration,
             ];
 
 
            // remplir les autres champs:
            $subscription = new Subscription();
            // $subscription = $form->getData();
            $subscription->setUser($user);
            $subscription->setInfosUser($fields);
            $subscription->setPaymentDate(new \DateTimeImmutable());
            $subscription->setInfosSubscription($subscriptionInfo);
            $subscription->setSubscriptionType($subscriptionType);
            $subscription->setTotal($total);
 
 
 
            $entityManager->persist($subscription);
            $entityManager->flush();
 
            //  $this->addFlash('success', 'success');
            //  return $this->redirectToRoute('app_home');
            //  dump($intent);die;
         
 
            return $this->json(['clientSecret' => $paymentIntent->client_secret]);
         
         return $this->render('payment/payment.html.twig', [
             'subscriptionType' => $subscriptionType,
            //  'formSubscriptionPayment' => $form,
             'clientSecret' => $clientSecret,
             '$intent' => $intent,
     
         ]);
     }
}
