<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Enhavo\Bundle\PageBundle\Form\Type\PageType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function showAction(Page $page)
    {
        return $this->render('EnhavoPageBundle:Frontend:show.html.twig', array(
            'page' => $page
        ));
    }
}
