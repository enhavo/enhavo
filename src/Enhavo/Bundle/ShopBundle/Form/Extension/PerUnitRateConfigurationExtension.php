<?php

namespace Enhavo\Bundle\ShopBundle\Form\Extension;

use Sylius\Bundle\ShippingBundle\Form\Type\Calculator\PerUnitRateConfigurationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerUnitRateConfigurationExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [PerUnitRateConfigurationType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currency' => 'EUR'
        ]);
    }
}
