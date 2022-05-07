<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Order;

use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateType extends AbstractType
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
                'order.state.new' => OrderInterface::STATE_NEW,
                'order.state.cancelled' => OrderInterface::STATE_CANCELLED,
                'order.state.fulfilled' => OrderInterface::STATE_FULFILLED,
            ],
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'order.form.label.state'
        ]);
    }
}
