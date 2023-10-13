<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends ResourceController
{
    public function showResourceAction(ProductInterface $contentDocument, Request $request): Response
    {
        $variant = $this->getProductManager()->getDefaultVariantProxy($contentDocument);
        if ($variant === null) {
            throw $this->createNotFoundException();
        }

        $variants = $this->getProductManager()->getVariantProxies($contentDocument->getVariants());

        return $this->render($this->resolveTemplate('theme/shop/product/show.html.twig'), [
            'product' => $contentDocument,
            'variants' => $variants,
            'variant' => $variant,
        ]);
    }

    public function listAction(Request $request): Response
    {
        return $this->render($this->resolveTemplate('theme/shop/product/list.html.twig'), [

        ]);
    }

    private function getProductManager(): ProductManager
    {
        return $this->get('enhavo_shop.product_manager');
    }
}
