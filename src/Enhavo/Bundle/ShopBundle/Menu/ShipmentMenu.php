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

class ShipmentMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'shopping_shipment',
            'label' => 'label.shipment',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_shipment_index',
            'role' => 'ROLE_ENHAVO_SHIPMENT_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_shipment';
    }
}
