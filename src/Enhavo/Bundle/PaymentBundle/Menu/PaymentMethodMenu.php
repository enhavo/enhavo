<?php

namespace Enhavo\Bundle\PaymentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'attach_money',
            'label' => 'payment_method.label.payment_method',
            'translation_domain' => 'EnhavoPaymentBundle',
            'route' => 'sylius_payment_method_index',
            'role' => 'ROLE_SYLIUS_PAYMENT_METHOD_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'payment_payment_method';
    }
}
