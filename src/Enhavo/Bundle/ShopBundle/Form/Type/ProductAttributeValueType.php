<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType as SyliusProductAttributeValueType;

class ProductAttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('localCode')
        ;
    }

    public function getParent()
    {
        return SyliusProductAttributeValueType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'enhavo_product_attribute_value';
    }
}
