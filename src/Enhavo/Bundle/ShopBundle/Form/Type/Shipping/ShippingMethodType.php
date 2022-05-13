<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Shipping;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Symfony\Component\Form\AbstractType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodType as BaseShippingMethodType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ShippingMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('translations');
        $builder->remove('enabled');

        $builder->add('name', TextType::class);
        $builder->add('enabled', BooleanType::class);
        $builder->add('taxCategory', TaxCategoryChoiceType::class);
    }


    public function getParent()
    {
        return BaseShippingMethodType::class;
    }
}
