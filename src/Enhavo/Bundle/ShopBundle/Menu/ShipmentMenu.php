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

class ShipmentMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'shopping_shipment',
            'label' => 'label.shipment',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_shipment_index',
            'role' => 'ROLE_ENHAVO_SHIPMENT_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_shipment';
    }
}
