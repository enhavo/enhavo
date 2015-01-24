<?php

namespace esperanto\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DemoController extends Controller
{
    public function indexAction()
    {
        return $this->render('esperantoProjectBundle:Demo:index.html.twig');
    }
}

