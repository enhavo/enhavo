<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Order;

use Enhavo\Bundle\ShopBundle\State\OrderPaymentStates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentStateType extends AbstractType
{
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'order.paymentState.awaitingPayment' => OrderPaymentStates::STATE_AWAITING_PAYMENT,
                'order.paymentState.paid' => OrderPaymentStates::STATE_PAID,
                'order.paymentState.partiallyPaid' => OrderPaymentStates::STATE_PARTIALLY_PAID,
                'order.paymentState.partiallyRefunded' => OrderPaymentStates::STATE_PARTIALLY_REFUNDED,
                'order.paymentState.refunded' => OrderPaymentStates::STATE_REFUNDED,
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.payment'
        ]);
    }
}
