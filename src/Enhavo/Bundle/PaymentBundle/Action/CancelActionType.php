<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return TransitionActionType::class;
    }

    public static function getName(): ?string
    {
        return 'payment_cancel';
    }
}
