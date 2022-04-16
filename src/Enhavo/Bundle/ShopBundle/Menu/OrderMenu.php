<?php
/**
 * OrderMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'shopping_cart',
            'label' => 'label.order',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_order_index',
            'role' => 'ROLE_ENHAVO_SHOP_ORDER_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_order';
    }
}
