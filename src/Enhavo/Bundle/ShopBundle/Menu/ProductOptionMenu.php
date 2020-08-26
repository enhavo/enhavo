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

class ProductOptionMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'sound-mix',
            'label' => 'product.label.product_option',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_product_option_index',
            'role' => 'ROLE_ENHAVO_SHOP_PRODUCT_OPTION_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_product_option';
    }
}