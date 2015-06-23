<?php

namespace esperanto\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('esperantoCalendarBundle:Default:index.html.twig', array('name' => $name));
    }
}
