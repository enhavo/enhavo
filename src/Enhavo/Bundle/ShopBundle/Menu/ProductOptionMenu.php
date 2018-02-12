<?php
/**
 * OrderMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class ProductOptionMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'sound-mix');
        $this->setDefaultOption('label', $options, 'product.label.product_option');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoShopBundle');
        $this->setDefaultOption('route', $options, 'enhavo_shop_product_option_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_SHOP_PRODUCT_OPTION_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'shop_product_option';
    }
}