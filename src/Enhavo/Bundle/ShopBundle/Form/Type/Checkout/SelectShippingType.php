<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class SelectShippingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shipments', CollectionType::class, [
            'entry_type' => ShipmentType::class,
            'label' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'enhavo_checkout_select_shipping';
    }
}
