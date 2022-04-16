<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\DownloadActionType;
use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingActionType extends DownloadActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'file_download',
            'label' => 'order.label.billing',
            'translation_domain' => 'EnhavoShopBundle',
            'route' => 'sylius_order_billing',
            'append_id' => true
        ]);
    }

    public function getType()
    {
        return 'shop_billing';
    }
}
