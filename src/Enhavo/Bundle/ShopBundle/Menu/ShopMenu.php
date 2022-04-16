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

class ShopMenu extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'store',
            'label' => 'label.shop',
            'translation_domain' => 'EnhavoShopBundle',
            'children' => [
                'order' => [
                    'type' => 'shop_order'
                ],
                'product' => [
                    'type' => 'shop_product'
                ],
                'productOption' => [
                    'type' => 'shop_product_option'
                ],
                'productAttribute' => [
                    'type' => 'shop_product_attribute'
                ],
                'productAssociation' => [
                    'type' => 'shop_product_association_type'
                ],
                'taxRate' => [
                    'type' => 'shop_tax_rate'
                ],
                'taxCategory' => [
                    'type' => 'shop_tax_category'
                ],
                'category' => [
                    'type' => 'shop_category'
                ],
                'tag' => [
                    'type' => 'shop_tag'
                ],
//                'voucher' => [
//                    'type' => 'shop_voucher'
//                ]
            ]
        ]);
    }

    public function getType()
    {
        return 'shop';
    }
}
