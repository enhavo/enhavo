<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePaymentMethodActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'create-payment-method-action',
            'icon' => 'add_circle_outline',
            'label' => 'label.create',
            'translation_domain' => 'EnhavoAppBundle',
            'frame_key' => 'edit-view',
            'target' => '_frame',
            'route' => null
        ]);
    }

    public static function getName(): ?string
    {
        return 'payment_create_payment_method';
    }
}
