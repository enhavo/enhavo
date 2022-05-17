<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'payment.action.complete',
            'translation_domain' => 'EnhavoPaymentBundle',
            'icon' => 'check',
            'transition' => 'complete',
            'condition' => 'resource.getState() in ["new", "processing", "authorized"]',
            'graph' => 'enhavo_payment',
            'route' => 'sylius_payment_update',
        ]);
    }

    public function getType()
    {
        return 'payment_complete';
    }
}
