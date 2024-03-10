<?php
 
namespace App\Controller;
 
use Dompdf\Dompdf;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class PdfGeneratorController extends AbstractController
{
    // #[Route('/pdf/generator/{id}', name: 'app_pdf_generator')]
    #[Route('/pdf/subscriptions_history/{id}', name: 'subscriptions_history')]
    public function index(User $user = null, Security $security, SubscriptionRepository $subscriptionRepo): Response
    {

        $user = $security->getUser();
        $userId = $user->getId();
        $totalSubscriptions = $subscriptionRepo->getTotalSubscriptions($userId);


        // Générer le HTML du PDF
        $html = $this->renderView('pdf_generator/subscriptionsHistory.html.twig', [
            'user' => $user,
            'totalSubscriptions' => $totalSubscriptions,
        ]);

        // Instanciation de Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Construire dynamiquement le nom du fichier PDF
        // Attention à nettoyer le nom d'utilisateur pour éviter les problèmes avec les caractères non autorisés dans les noms de fichiers
        $pdfFileName = 'SUBSCRIPTIONS_HISTORY_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->getUsername());
         
        return new Response (
            $dompdf->stream($pdfFileName, ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }


    // doesn't render a view / use to attach the pdf file in emails
    // #[Route('/pdf/generator', name: 'app_pdf_generator')]
    // public function generatePdfContent(): string
    // {
    //     $html = $this->renderView('pdf_generator/index.html.twig', []);

    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml($html);
    //     $dompdf->render();

    //     return $dompdf->output();
    // }
 
}