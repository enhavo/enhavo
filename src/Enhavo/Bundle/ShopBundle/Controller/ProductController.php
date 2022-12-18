<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Product\ProductVariantResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends ResourceController
{
    use TemplateTrait;

    public function showResourceAction(ProductInterface $contentDocument, Request $request): Response
    {
        $variant = $this->getProductManager()->getDefaultVariantProxy($contentDocument);
        if ($variant === null) {
            throw $this->createNotFoundException();
        }

        $variants = $this->getProductManager()->getVariantProxies($contentDocument->getVariants());

        return $this->render($this->getTemplate('theme/shop/product/show.html.twig'), [
            'product' => $contentDocument,
            'variants' => $variants,
            'variant' => $variant,
        ]);
    }

    public function listAction(Request $request): Response
    {
        return $this->render($this->getTemplate('theme/shop/product/list.html.twig'), [

        ]);
    }

    private function getProductManager(): ProductManager
    {
        return $this->get('enhavo_shop.product_manager');
    }
}
