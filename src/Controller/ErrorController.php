<?php

namespace App\Controller;

use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ErrorController extends AbstractController
{
    public function show(Throwable $exception): Response
    {
        // Check if the exception is an AccessDeniedException
        if ($exception instanceof AccessDeniedHttpException) {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }
        
        // For non-AccessDeniedException exceptions, render a 404 error page
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }
}



