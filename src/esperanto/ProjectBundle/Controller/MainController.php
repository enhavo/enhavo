<?php

namespace esperanto\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }

    public function pageAction()
    {
        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }

    public function newsAction()
    {
        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }

    public function referenceAction()
    {
        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }
}

