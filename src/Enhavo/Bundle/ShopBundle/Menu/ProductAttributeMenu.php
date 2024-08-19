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

class ProductAttributeMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'sound-mix',
            'label' => 'product.label.product_attribute',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_product_attribute_index',
            'role' => 'ROLE_ENHAVO_SHOP_PRODUCT_ATTRIBUTE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_product_attribute';
    }
}
