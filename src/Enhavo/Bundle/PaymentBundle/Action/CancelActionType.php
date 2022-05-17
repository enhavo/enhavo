<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'payment.action.cancel',
            'translation_domain' => 'EnhavoPaymentBundle',
            'icon' => 'cancel',
            'transition' => 'cancel',
            'condition' => 'resource.getState() in ["new", "processing", "authorized"]',
            'graph' => 'enhavo_payment',
            'route' => 'sylius_order_update',
        ]);
    }

    public function getType()
    {
        return 'payment_cancel';
    }
}
