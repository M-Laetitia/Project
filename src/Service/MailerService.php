<?php

namespace App\Service;

use App\Entity\Area;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // public function sendWelcomeEmail($to, $username, $registrationDate)
    // {
    //     $subject = 'Welcome to Our Website';
    //     $message = sprintf(
    //         '<p>Thank you, %s, for registering on %s. Your registration date is %s.</p>',
    //         $username,
    //         'Your Website Name',
    //         $registrationDate->format('Y-m-d H:i:s')
    //     );

    //     $this->sendEmail($to, $subject, $message);
    // }



    public function sendExpositionProposalConfirmation($to, $expositionDetails)
    {
        $subject = 'Exposition Proposal Confirmation';
        $message = '<p>Thank you for your proposal for the exposition details:</p>' . $expositionDetails;
        // $message = $this->generateExpositionConfirmationMessage($expositionDetails);

        $this->sendEmail($to, $subject, $message);
    }

    public function sendExpositionConfirmationEmail($expo, array $usersToNotify) {
        $subject = 'Exposition Confirmation: ' . $expo->getName();
        $message = '<p>Thank you! The exposition ' . $expo->getName() . ' has reached the required number of proposals.</p>';
        foreach ($usersToNotify as $email) {
            // Envoyer un e-mail à chaque utilisateur
            $this->sendEmail($email, $subject, $message);
        }
    }

    private function sendEmail($to, $subject, $message)
    {
        $email = (new Email())
            ->from('admin@example.com')
            ->to($to)
            ->subject($subject)
            ->html($message);

        $this->mailer->send($email);
    }

    // private function generateExpositionConfirmationMessage($expositionDetails)
    // {
    //     return '<p>Thank you for your proposal for the exposition details:</p>' . $expositionDetails;
    // }

    // private function generateExpositionConfirmationMessage(Area $expo)
    // {
    //     // Construisez le message HTML en utilisant les détails de l'exposition
    //     $message = '<p>Thank you for your interest in the exposition:</p>';
    //     $message .= '<p>Name: ' . $expo->getName() . '</p>';
    //     $message .= '<p>Description: ' . $expo->getDescription() . '</p>';
    //     $message .= '<p>Dates: ' . $expo->getStartDate()->format('Y-m-d') . ' - ' . $expo->getEndDate()->format('Y-m-d') . '</p>';
    //     // Ajoutez d'autres détails de l'exposition selon vos besoins

    //     return $message;
    // }
}
