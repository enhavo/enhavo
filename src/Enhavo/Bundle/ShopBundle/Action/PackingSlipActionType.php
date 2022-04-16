<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\DownloadActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackingSlipActionType extends DownloadActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'file_download',
            'label' => 'order.label.packing_slip',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_order_packing_slip',
            'append_id' => true
        ]);
    }

    public function getType()
    {
        return 'shop_packing_slip';
    }
}
