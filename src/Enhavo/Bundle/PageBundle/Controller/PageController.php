<?php

namespace enhavo\PageBundle\Controller;

use enhavo\PageBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use enhavo\PageBundle\Form\Type\PageType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function showAction(Page $page)
    {
        return $this->render('enhavoPageBundle:Frontend:show.html.twig', array(
            'page' => $page
        ));
    }
}
