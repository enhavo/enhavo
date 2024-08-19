<?php
/**
 * OrderMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingMethodMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'directions_boat',
            'label' => 'shipping_method.label.shipping_method',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_shipping_method_index',
            'role' => 'ROLE_SYLIUS_SHIPPING_METHOD_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_shipping_method';
    }
}
