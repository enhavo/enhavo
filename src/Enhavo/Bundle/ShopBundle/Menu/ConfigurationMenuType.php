<?php
/**
 * OrderMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\ListMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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
            ]
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_configuration';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
