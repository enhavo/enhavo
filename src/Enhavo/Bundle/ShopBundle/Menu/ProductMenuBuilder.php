<?php
/**
 * ProductMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class ProductMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'box');
        $this->setOption('label', $options, 'label.product');
        $this->setOption('translationDomain', $options, 'EnhavoShopBundle');
        $this->setOption('route', $options, 'enhavo_shop_product_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_SHOP_PRODUCT_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'shop_product';
    }
}