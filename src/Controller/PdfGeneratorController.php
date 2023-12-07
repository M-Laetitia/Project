<?php
 
namespace App\Controller;
 
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class PdfGeneratorController extends AbstractController
{
    // #[Route('/pdf/generator/{id}', name: 'app_pdf_generator')]
    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function index(): Response
    {

        $html = $this->renderView('pdf_generator/index.html.twig', [

        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }


    // doesn't render a view / use to attach the pdf file in emails
    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function generatePdfContent(): string
    {
        $html = $this->renderView('pdf_generator/index.html.twig', []);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }
 
}