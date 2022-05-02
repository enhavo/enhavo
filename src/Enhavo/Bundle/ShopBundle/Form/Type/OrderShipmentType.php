<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderShipmentType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('shipments', CollectionType::class, [
            'entry_type' => ShipmentType::class,
            'label' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_order_shipment';
    }
}
