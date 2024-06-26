<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return TransitionActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_order_ship';
    }
}
