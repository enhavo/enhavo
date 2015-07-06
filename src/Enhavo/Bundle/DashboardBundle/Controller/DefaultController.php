<?php

namespace Enhavo\Bundle\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EnhavoDashboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
