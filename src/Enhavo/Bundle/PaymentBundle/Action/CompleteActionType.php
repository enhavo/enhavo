<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return TransitionActionType::class;
    }

    public static function getName(): ?string
    {
        return 'payment_complete';
    }
}
