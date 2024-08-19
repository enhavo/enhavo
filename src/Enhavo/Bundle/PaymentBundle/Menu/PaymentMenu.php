<?php

namespace Enhavo\Bundle\PaymentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'shopping_payment',
            'label' => 'payment.label.payment',
            'translation_domain' => 'EnhavoPaymentBundle',
            'route' => 'sylius_payment_index',
            'role' => 'ROLE_ENHAVO_PAYMENT_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'payment_payment';
    }
}
