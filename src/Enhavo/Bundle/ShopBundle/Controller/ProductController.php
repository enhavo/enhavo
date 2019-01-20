<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends ResourceController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoShopBundle:Theme:Product/show.html.twig', [
            'product' => $contentDocument
        ]);
    }

    public function listAction(Request $request): Response
    {
        return $this->render('EnhavoShopBundle:Theme:Product/list.html.twig', [
            
        ]);
    }
}
