<?php
/**
 * OrderMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\ListMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalesMenu extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'attach_money',
            'label' => 'label.sales',
            'translation_domain' => 'EnhavoShopBundle',
            'children' => [
                'order' => [
                    'type' => 'shop_order'
                ],
                'payment' => [
                    'type' => 'payment_payment'
                ],
                'shipment' => [
                    'type' => 'shop_shipment'
                ],
            ]
        ]);
    }

    public function getType()
    {
        return 'shop_sales';
    }
}
