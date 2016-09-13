<?php
/**
 * ProductListRenderer.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Render;


class ProductListRenderer extends AbstractRenderer
{
    public function render($options)
    {
        $resolvedOptions = $this->resolveOptions([
            'template' => 'EnhavoShopBundle:Product:list.html.twig'
        ], $options);

        $products = $this->get('enhavo_shop.repository.product')->findPublished();

        return $this->renderTemplate($resolvedOptions['template'], [
            'products' => $products
        ]);
    }
}