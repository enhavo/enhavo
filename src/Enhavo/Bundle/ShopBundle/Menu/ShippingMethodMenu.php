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

class ShippingMethodMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'directions_boat',
            'label' => 'shipping_method.label.shipping_method',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_shipping_method_index',
            'role' => 'ROLE_SYLIUS_SHIPPING_METHOD_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_shipping_method';
    }
}
