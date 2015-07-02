<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('enhavoCalendarBundle:Default:index.html.twig', array('name' => $name));
    }
}
