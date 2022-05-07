<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaidActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'order.action.paid',
            'translation_domain' => 'EnhavoShopBundle',
            'icon' => 'payment',
            'transition' => 'pay',
            'condition' => 'resource.getPaymentState() in ["awaiting_payment", "partially_paid", "authorized"]',
            'graph' => 'enhavo_order_payment',
            'route' => 'sylius_order_update',
        ]);
    }

    public function getType()
    {
        return 'shop_order_paid';
    }
}
