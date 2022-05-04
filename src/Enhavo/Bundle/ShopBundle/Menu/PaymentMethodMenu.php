<?php

namespace Enhavo\Bundle\ShopBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'attach_money',
            'label' => 'payment_method.label.payment_method',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_payment_method_index',
            'role' => 'ROLE_SYLIUS_PAYMENT_METHOD_INDEX',
        ]);
    }

    public function getType()
    {
        return 'shop_payment_method';
    }
}
