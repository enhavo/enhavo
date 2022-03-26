<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends ResourceController
{
    use TemplateTrait;

    public function showResourceAction($contentDocument)
    {
        return $this->render($this->getTemplate('theme/shop/product/show.html.twig'), [
            'product' => $contentDocument
        ]);
    }

    public function listAction(Request $request): Response
    {
        return $this->render($this->getTemplate('theme/shop/product/list.html.twig'), [

        ]);
    }
}
