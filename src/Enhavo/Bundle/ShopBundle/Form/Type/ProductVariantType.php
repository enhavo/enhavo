<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Config\Condition;
use Enhavo\Bundle\FormBundle\Form\Config\ConditionObserver;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType as SyliusProductVariantType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantType extends AbstractType
{
    public function __construct(
        protected string $dataClass,
        private string $taxCategoryClass,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enabled', BooleanType::class, array(
            'label' => 'product_variant.form.label.active',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('default', BooleanType::class, array(
            'label' => 'product_variant.form.label.default',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('index', BooleanType::class, array(
            'label' => 'product_variant.form.label.index',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('title', TextType::class, array(
            'label' => 'product_variant.form.label.title',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $descriptionOverrideCondition = new Condition();
        $builder->add('overrideDescription', BooleanType::class, array(
            'label' => 'product_variant.form.label.overrideDescription',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $descriptionOverrideCondition,
        ));

        $builder->add('description', WysiwygType::class, array(
            'label' => 'product_variant.form.label.description',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $descriptionOverrideCondition->createObserver(true),
        ));

        $picturesOverrideCondition = new Condition();
        $builder->add('overridePictures', BooleanType::class, array(
            'label' => 'product_variant.form.label.overridePictures',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $picturesOverrideCondition,
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'product_variant.form.label.picture',
            'translation_domain' => 'EnhavoShopBundle',
            'multiple' => false,
            'condition_observer' => $picturesOverrideCondition->createObserver(true),
        ));

        $builder->add('pictures', MediaType::class, array(
            'multiple' => true,
            'label' => 'product_variant.form.label.pictures',
            'translation_domain' => 'EnhavoShopBundle',
            'required' => false,
            'condition_observer' => $picturesOverrideCondition->createObserver(true),
        ));

        $builder->add('code', TextType::class, array(
            'label' => 'product_variant.form.label.code',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $priceOverrideCondition = new Condition();
        $builder->add('overridePrice', BooleanType::class, array(
            'label' => 'product_variant.form.label.overridePrice',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $priceOverrideCondition,
        ));

        $builder->add('price', CurrencyType::class, array(
            'label' => 'product.form.label.price',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $priceOverrideCondition->createObserver(true),
        ));

        $builder->add('reducedPrice', CurrencyType::class, array(
            'label' => 'product_variant.form.label.reducedPrice',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $priceOverrideCondition->createObserver(true),
        ));

        $builder->add('reduced', BooleanType::class, array(
            'label' => 'product_variant.form.label.reduced',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $priceOverrideCondition->createObserver(true),
        ));

        $shippingCategoryCondition = new Condition();
        $builder->add('overrideShipping', BooleanType::class, array(
            'label' => 'product_variant.form.label.overrideShipping',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $shippingCategoryCondition,
        ));

        $builder->add('shippingCategory', ShippingCategoryChoiceType::class, array(
            'label' => 'product_variant.form.label.shipping_category',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $shippingCategoryCondition->createObserver(true),
        ));

        $builder->add('shippingRequired', BooleanType::class, array(
            'label' => 'product_variant.form.label.shipping_required',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $shippingCategoryCondition->createObserver(true),
        ));


        $stockCondition = new Condition();
        $builder->add('stockTracked', BooleanType::class, array(
            'label' => 'product_variant.form.label.stock_tracked',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $stockCondition,
        ));

        $builder->add('stock', IntegerType::class, array(
            'label' => 'product_variant.form.label.stock',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $stockCondition->createObserver(true),
        ));

        $dimensionOverrideCondition = new Condition();
        $builder->add('overrideDimensions', BooleanType::class, array(
            'label' => 'product_variant.form.label.overrideDimensions',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $dimensionOverrideCondition,
        ));

        $builder->add('width', TextType::class, array(
            'label' => 'product.form.label.width',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('height', TextType::class, array(
            'label' => 'product.form.label.height',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('depth', TextType::class, array(
            'label' => 'product.form.label.depth',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('weight', TextType::class, array(
            'label' => 'product.form.label.weight',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('volume', TextType::class, array(
            'label' => 'product.form.label.volume',
            'translation_domain' => 'EnhavoShopBundle',
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('volumeUnit', ChoiceType::class, array(
            'label' => 'product.form.label.volumeUnit',
            'translation_domain' => 'EnhavoShopBundle',
            'choices' => [
                'ml' => 'ml',
                'l' => 'l',
            ],
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('weightUnit', ChoiceType::class, array(
            'label' => 'product.form.label.weightUnit',
            'translation_domain' => 'EnhavoShopBundle',
            'choices' => [
                'mg' => 'mg',
                'g' => 'g',
                'kg' => 'kg',
                't' => 't'
            ],
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $builder->add('lengthUnit', ChoiceType::class, array(
            'label' => 'product.form.label.lengthUnit',
            'translation_domain' => 'EnhavoShopBundle',
            'choices' => [
                'mm' => 'mm',
                'cm' => 'cm',
                'm' => 'm',
            ],
            'condition_observer' => $dimensionOverrideCondition->createObserver(true),
        ));

        $taxCategoryCondition = new Condition();
        $builder->add('overrideTaxCategory', BooleanType::class, array(
            'label' => 'product_variant.form.label.overrideTaxCategory',
            'translation_domain' => 'EnhavoShopBundle',
            'condition' => $taxCategoryCondition,
        ));

        $builder->add('taxCategory', EntityType::class, array(
            'class' => $this->taxCategoryClass,
            'choice_label' => 'name',
            'label' => 'product.form.label.taxCategory',
            'translation_domain' => 'EnhavoShopBundle',
            'placeholder' => '---',
            'condition_observer' => $taxCategoryCondition->createObserver(true),
        ));

        $builder->add('slug', TextType::class, array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoContentBundle',
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'validation_groups' => ['product-variant']
        ));
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
