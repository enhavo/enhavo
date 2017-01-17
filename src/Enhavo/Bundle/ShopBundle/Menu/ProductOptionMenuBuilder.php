<?php
/**
 * OrderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class ProductOptionMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'sound-mix');
        $this->setOption('label', $options, 'product.label.product_option');
        $this->setOption('translationDomain', $options, 'EnhavoShopBundle');
        $this->setOption('route', $options, 'enhavo_shop_product_option_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_SHOP_PRODUCT_OPTION_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'shop_product_option';
    }
}