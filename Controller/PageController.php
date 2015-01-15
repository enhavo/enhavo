<?php

namespace esperanto\PageBundle\Controller;

use esperanto\PageBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use esperanto\PageBundle\Form\Type\PageType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function showAction($id, $slug)
    {
        $repository = $this->get('esperanto_page.repository.page');
        $page = $repository->find($id);

        if(empty($page)) {
            throw $this->createNotFoundException();
        }

        return $this->render('esperantoPageBundle:Frontend:show.html.twig', array(
            'page' => $page
        ));
    }
}
