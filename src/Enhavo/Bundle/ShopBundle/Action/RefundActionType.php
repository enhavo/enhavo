<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\TransitionActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RefundActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return TransitionActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_order_refund';
    }
}
