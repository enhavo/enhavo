<?php
/**
 * ProductMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class ProductMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'box');
        $this->setDefaultOption('label', $options, 'label.product');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoShopBundle');
        $this->setDefaultOption('route', $options, 'enhavo_shop_product_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_SHOP_PRODUCT_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'shop_product';
    }
}