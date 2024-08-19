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

class SalesMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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
                'voucher' => [
                    'type' => 'shop_voucher'
                ]
            ]
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_sales';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
