<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function listAction(Request $request)
    {

        return $this->render('EnhavoShopBundle:Product:list.html.twig', [

        ]);
    }
}