<?php

namespace esperanto\ProjectBundle\Controller;

use esperanto\ProjectBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('esperantoProjectBundle:News:index.html.twig');
    }

    public function pageAction(Page $contentDocument)
    {
        return $this->render('esperantoProjectBundle:Page:page.html.twig', array(
            'page' => $contentDocument
        ));
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

