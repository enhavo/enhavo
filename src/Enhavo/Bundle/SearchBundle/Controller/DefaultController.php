<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('enhavoSearchBundle:Default:index.html.twig', array('name' => $name));
    }
}
