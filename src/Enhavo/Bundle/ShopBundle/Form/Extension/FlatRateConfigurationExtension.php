<?php

namespace Enhavo\Bundle\ShopBundle\Form\Extension;

use Sylius\Bundle\ShippingBundle\Form\Type\Calculator\FlatRateConfigurationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlatRateConfigurationExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [FlatRateConfigurationType::class,];
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currency' => 'EUR'
        ]);
    }
}
