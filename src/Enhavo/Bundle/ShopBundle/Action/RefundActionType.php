<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RefundActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'order.action.refund',
            'translation_domain' => 'EnhavoShopBundle',
            'icon' => 'settings_backup_restore',
            'type' => 'transition',
            'transition' => 'refund',
            'condition' => 'resource.getPaymentState() in ["paid", "partially_paid", "partially_refunded"]',
            'graph' => 'enhavo_order_payment',
            'route' => 'sylius_order_update',
        ]);
    }

    public function getType()
    {
        return 'shop_order_refund';
    }
}
