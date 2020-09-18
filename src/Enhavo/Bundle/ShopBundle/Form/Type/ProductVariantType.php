<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
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
     * @var string
     */
    private $taxRateClass;

    /**
     * ProductVariantType constructor.
     *
     * @param $dataClass
     */
    public function __construct($dataClass, $taxRateClass)
    {
        $this->dataClass = $dataClass;
        $this->taxRateClass = $taxRateClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'product_variant.label.title',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'product_variant.label.picture',
            'translation_domain' => 'EnhavoShopBundle',
            'multiple' => false
        ));

        $builder->add('code', TextType::class, array(
            'label' => 'product_variant.label.code',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('price', CurrencyType::class, array(
            'label' => 'product.label.price',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingCategory', ShippingCategoryChoiceType::class, array(
            'label' => 'product_variant.label.shipping_category',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingRequired', BooleanType::class, array(
            'label' => 'product_variant.label.shipping_required',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('stock', IntegerType::class, array(
            'label' => 'product_variant.label.stock',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('stockTracked', BooleanType::class, array(
            'label' => 'product_variant.label.stock_tracked',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('width', TextType::class, array(
            'label' => 'product_variant.label.width',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('height', TextType::class, array(
            'label' => 'product_variant.label.height',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('depth', TextType::class, array(
            'label' => 'product_variant.label.depth',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('weight', TextType::class, array(
            'label' => 'product_variant.label.weight',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('volume', TextType::class, array(
            'label' => 'product_variant.label.volume',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('taxRate', EntityType::class, array(
            'class' => $this->taxRateClass,
            'choice_label' => 'name',
            'label' => 'product_variant.label.taxRate',
            'translation_domain' => 'EnhavoShopBundle',
        ));

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
