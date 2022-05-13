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

class PaymentMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'shopping_payment',
            'label' => 'label.payment',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_payment_index',
            'role' => 'ROLE_ENHAVO_PAYMENT_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_payment';
    }
}
