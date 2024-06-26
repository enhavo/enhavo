<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return TransitionActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_order_cancel';
    }
}
