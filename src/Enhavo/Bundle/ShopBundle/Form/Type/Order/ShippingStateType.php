<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Order;

use Enhavo\Bundle\ShopBundle\State\OrderShippingStates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingStateType extends AbstractType
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
                'order.shippingState.ready' => OrderShippingStates::STATE_READY,
                'order.shippingState.partiallyShipped' => OrderShippingStates::STATE_PARTIALLY_SHIPPED,
                'order.shippingState.shipped' => OrderShippingStates::STATE_SHIPPED,
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.shipping'
        ]);
    }
}
