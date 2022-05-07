<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelActionType extends TransitionActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'order.action.cancel',
            'translation_domain' => 'EnhavoShopBundle',
            'icon' => 'cancel',
            'transition' => 'cancel',
            'condition' => 'resource.getState() == "new"',
            'graph' => 'enhavo_order',
            'route' => 'sylius_order_update',
        ]);
    }

    public function getType()
    {
        return 'shop_order_cancel';
    }
}
