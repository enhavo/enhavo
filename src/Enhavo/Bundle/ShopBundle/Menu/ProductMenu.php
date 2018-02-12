<?php
/**
 * ProductMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'box',
            'label' =>  'label.product',
            'translationDomain' => 'EnhavoShopBundle',
            'route' => 'enhavo_shop_product_index',
            'role' => 'ROLE_ENHAVO_SHOP_PRODUCT_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_product';
    }
}