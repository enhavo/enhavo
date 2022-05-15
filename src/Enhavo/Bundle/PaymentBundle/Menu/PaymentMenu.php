<?php

namespace Enhavo\Bundle\PaymentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'shopping_payment',
            'label' => 'payment.label.payment',
            'translation_domain' => 'EnhavoPaymentBundle',
            'route' => 'sylius_payment_index',
            'role' => 'ROLE_ENHAVO_PAYMENT_INDEX',
        ]);
    }

    public function getType()
    {
        return 'payment_payment';
    }
}
