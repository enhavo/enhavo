<?php
/**
 * ProductListConfigurator.php
 *
 * @since 13/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Widget;

use Enhavo\Bundle\ShopBundle\Entity\Product;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class ProductConfiguratorWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'shop_product_configurator';
    }

    public function render($options)
    {
        $resolvedOptions = $this->resolveOptions([
            'template' => 'EnhavoShopBundle:Theme:Widget/product-configurator.html.twig',
            'product' => null
        ], $options);

        /** @var Product $product */
        $product = $resolvedOptions['product'];
        if($product === null) {
            throw new \Exception('Need to pass a product to ProductConfiguratorWidget');
        }
        
        return $this->renderTemplate($resolvedOptions['template'], [
            'product' => $product
        ]);
    }
}