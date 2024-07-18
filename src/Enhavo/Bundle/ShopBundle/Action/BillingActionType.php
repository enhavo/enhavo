<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\DownloadActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'file_download',
            'label' => 'order.label.billing',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_order_billing',
            'append_id' => true
        ]);
    }

    public static function getParentType(): ?string
    {
        return DownloadActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_billing';
    }
}
