<?php
/**
 * ProductListWidget.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Widget;

use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class ProductListWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'shop_product_list';
    }

    public function render($options)
    {
        $resolvedOptions = $this->resolveOptions([
            'template' => 'EnhavoShopBundle:Theme:Widget/product-list.html.twig'
        ], $options);

        $products = $this->container->get('sylius.repository.product')->findAll();

        return $this->renderTemplate($resolvedOptions['template'], [
            'products' => $products
        ]);
    }
}