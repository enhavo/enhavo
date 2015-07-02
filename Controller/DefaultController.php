<?php

namespace esperanto\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('esperantoShopBundle:Default:index.html.twig', array('name' => $name));
    }
}
