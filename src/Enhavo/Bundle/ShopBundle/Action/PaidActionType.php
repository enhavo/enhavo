<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\TransitionActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaidActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return TransitionActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_order_paid';
    }
}
