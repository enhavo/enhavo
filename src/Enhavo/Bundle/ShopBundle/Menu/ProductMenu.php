<?php
/**
 * ProductMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'work',
            'label' =>  'label.product',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_product_index',
            'role' => 'ROLE_SYLIUS_PRODUCT_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_product';
    }
}
