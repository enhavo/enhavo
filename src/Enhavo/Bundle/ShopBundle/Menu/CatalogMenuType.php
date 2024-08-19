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

class CatalogMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getName(): ?string
    {
        return 'shop_catalog';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
