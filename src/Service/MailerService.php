<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
}
