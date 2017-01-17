<?php
/**
 * OrderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class OrderMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'shopping-cart');
        $this->setOption('label', $options, 'label.order');
        $this->setOption('translationDomain', $options, 'EnhavoShopBundle');
        $this->setOption('route', $options, 'enhavo_shop_order_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_SHOP_ORDER_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'shop_order';
    }
}