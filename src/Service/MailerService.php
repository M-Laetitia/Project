<?php

namespace App\Service;

use App\Entity\Area;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use App\Controller\PdfGeneratorController;
use Symfony\Component\Mailer\MailerInterface;


class MailerService
{
    private $mailer;
    private $pdfGeneratorController;

    public function __construct(MailerInterface $mailer, PdfGeneratorController $pdfGeneratorController)
    {
        $this->mailer = $mailer;
        $this->pdfGeneratorController = $pdfGeneratorController;
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

    // ^  Event participation confirmation :
    public function sendEventParticipationConfirmation($to, $expositionDetails)
    {
        $subject = 'Event participation confirmation';
        $message = '<p>Thank you for your participation in this event:</p>' . $expositionDetails;
        $this->sendEmail($to, $subject, $message);
    }


    public function sendExpositionProposalConfirmation($to, $expositionDetails)
    {
        $subject = 'Exposition Proposal Confirmation';
        $message = '<p>Thank you for your proposal for the exposition details:</p>' . $expositionDetails;
        $this->sendEmail($to, $subject, $message);
    }

    public function sendExpositionParticipationConfirmation($to, $expositionDetails)
    {
        $subject = 'Exposition participation confirmation';
        $message = '<p>Thank you for your participation in this exposition:</p>' . $expositionDetails;
        $this->sendEmail($to, $subject, $message);
    }

    // public function sendExpositionConfirmation($to, $expositionDetails)
    // {
    //     $subject = 'Exposition opening confirmation';
    //     $message = '<p>The exposition has obtained the necessary number of artist applications, and will therefore take place. Thank you for your participation:</p>' . $expositionDetails;
    //     $this->sendEmail($to, $subject, $message);
    // }




    public function sendExpositionConfirmation($expo, array $usersToNotify) {
    $subject = 'Exposition Confirmation: ' . $expo->getName();
    $message = '<p>Thank you! The exposition ' . $expo->getName() . ' has reached the required number of proposals, and will therefore take place. Thank you for your participation</p>';

    // Generate PDF content using PdfGeneratorController
    $pdfContent = $this->pdfGeneratorController->generatePdfContent();
    // dump($pdfContent);die;

    foreach ($usersToNotify as $emailAddress) {
        // Create a new Email instance for each user
        $email = (new Email())
            ->from('admin@example.com') // Set the sender's email
            ->to($emailAddress)
            ->subject($subject)
            ->html($message);

        // Attach the PDF to the email
        // $email->attach($pdfContent, 'exposition_confirmation.pdf', 'application/pdf');

        // Send the email
        $this->mailer->send($email);
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
