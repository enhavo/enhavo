<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'payment.action.reset',
            'translation_domain' => 'EnhavoPaymentBundle',
            'icon' => 'keyboard_backspace',
            'transition' => 'reset',
            'condition' => 'resource.getState() in ["new", "processing", "authorized", "fail"]',
            'graph' => 'enhavo_payment',
            'route' => 'sylius_payment_update',
        ]);
    }

    public function getType()
    {
        return 'payment_reset';
    }
}
