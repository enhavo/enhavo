<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/error')]
class ErrorController extends AbstractController
{
    #[Route('/default')]
    public function showDefaultAction()
    {
        throw new RuntimeException();
    }

    #[Route('/404')]
    public function show404Action()
    {
        throw $this->createNotFoundException();
    }

    #[Route('/403')]
    public function show403Action()
    {
        throw $this->createAccessDeniedException();
    }
}
