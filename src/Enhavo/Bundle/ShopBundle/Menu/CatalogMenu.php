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

class CatalogMenu extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'store',
            'label' => 'label.catalog',
            'translation_domain' => 'EnhavoShopBundle',
            'children' => [
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
                'category' => [
                    'type' => 'shop_category'
                ],
                'tag' => [
                    'type' => 'shop_tag'
                ],
            ]
        ]);
    }

    public function getType()
    {
        return 'shop_catalog';
    }
}
