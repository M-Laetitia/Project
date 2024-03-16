<?php

namespace App\Controller;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Get the value of the "formation" field directly from the form (formation = email / honey pot)
            $formation = $form->get('information')->getData();
            
             // Check if the email address already exists
            $existingUser = $userRepository->findOneBy(['email' => $formation]);

            if ($existingUser) {
                // The email address already exists, display an error message and redirect
                $this->addFlash('error', 'This mail already exists');
                return $this->redirectToRoute('app_register');
            }


            // check if username already exists
            $username = $form->get('username')->getData();
            $existingUsername = $userRepository->findOneBy(['username' => $username]);
            if ($existingUsername) {
                $this->addFlash('error', 'This mail already exists');
                return $this->redirectToRoute('app_register');
            }


            $user = new User();
            $user = $form->getData();
            $user->setEmail($formation); 
            $user->setRegistrationDate(new \DateTimeImmutable());
            $user->setIsPublished(0);

            // $slugify = new Slugify();
            // $slug = $slugify->slugify($username);
            // // dd($slug);
            // $user->setSlug($slug);
            
            
            $entityManager->persist($user);
            $user->setSlug($user->generateSlug());
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@exemple.com', 'Admin Website'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    /**
     * @Route("/check-username", name="check-username", methods={"POST"})
    */
    #[Route('/check-username', name: 'check-username', methods: ['POST'])]
    public function checkUsername(Request $request, UserRepository $userRepository)
    {

        $username = $request->request->get('username');
        $existingUsername = $userRepository->findOneBy(['username' => $username]);

        if ($existingUsername) {
                $response = [
                    'success' => false,
                    'message' => 'Username already exists',
                ];
            } else {
                $response = [
                    'success' => true,
                    'message' => 'Username is available',
                ];
            }

        return new JsonResponse($response);
    }
}
