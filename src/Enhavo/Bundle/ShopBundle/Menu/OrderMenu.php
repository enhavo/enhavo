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

class OrderMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'shopping_cart',
            'label' => 'label.order',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_order_index',
            'role' => 'ROLE_ENHAVO_SHOP_ORDER_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_order';
    }
}
