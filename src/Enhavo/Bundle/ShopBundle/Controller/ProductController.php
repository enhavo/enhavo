<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function listAction(Request $request)
    {
        $products = $this->get('enhavo_shop.repository.product')->findPublished();

        return $this->render('EnhavoShopBundle:Product:list.html.twig', [
            'products' => $products
        ]);
    }
}