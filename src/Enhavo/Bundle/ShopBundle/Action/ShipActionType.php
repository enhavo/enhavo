<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'order.action.ship',
            'translation_domain' => 'EnhavoShopBundle',
            'icon' => 'local_shipping',
            'type' => 'transition',
            'transition' => 'ship',
            'condition' => 'resource.getShippingState() in ["ready", "partially_shipped"]',
            'graph' => 'enhavo_order_shipping',
            'route' => 'sylius_order_update',
        ]);
    }

    public function getType()
    {
        return 'shop_order_ship';
    }
}
