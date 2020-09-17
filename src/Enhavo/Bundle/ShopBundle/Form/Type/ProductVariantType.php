<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType as SyliusProductVariantType;

class ProductVariantType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * ProductVariantType constructor.
     *
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', IntegerType::class);
    }

    public function getParent()
    {
        return SyliusProductVariantType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_product_variant';
    }
}
