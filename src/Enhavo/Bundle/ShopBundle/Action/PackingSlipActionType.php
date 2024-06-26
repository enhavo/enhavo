<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\DownloadActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackingSlipActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'file_download',
            'label' => 'order.label.packing_slip',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_order_packing_slip',
            'append_id' => true
        ]);
    }

    public static function getParentType(): ?string
    {
        return DownloadActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_packing_slip';
    }
}
