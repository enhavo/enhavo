<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends ResourceController
{
    public function listAction(Request $request)
    {
        return $this->render('EnhavoShopBundle:Theme:Product/list.html.twig', [
            
        ]);
    }
}