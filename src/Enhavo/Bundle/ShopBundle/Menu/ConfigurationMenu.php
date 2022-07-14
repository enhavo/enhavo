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

class ConfigurationMenu extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'settings',
            'label' => 'label.configuration',
            'translation_domain' => 'EnhavoShopBundle',
            'children' => [
                'taxRate' => [
                    'type' => 'shop_tax_rate'
                ],
                'taxCategory' => [
                    'type' => 'shop_tax_category'
                ],
                'shippingMethod' => [
                    'type' => 'shop_shipping_method'
                ],
                'paymentMethod' => [
                    'type' => 'payment_payment_method'
                ],
                'country' => [
                    'type' => 'shop_country'
                ],
                'voucher' => [
                    'type' => 'shop_voucher'
                ]
            ]
        ]);
    }

    public function getType()
    {
        return 'shop_configuration';
    }
}
