<?php

namespace Enhavo\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EnhavoContentBundle:Default:index.html.twig', array('name' => $name));
    }
}
