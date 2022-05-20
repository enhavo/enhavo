<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
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

        $builder->add('description', WysiwygType::class, array(
            'label' => 'product_variant.form.label.description',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'product_variant.form.label.picture',
            'translation_domain' => 'EnhavoShopBundle',
            'multiple' => false
        ));

        $builder->add('pictures', MediaType::class, array(
            'multiple' => true,
            'label' => 'product_variant.form.label.pictures',
            'translation_domain' => 'EnhavoShopBundle',
            'required' => false
        ));

        $builder->add('code', TextType::class, array(
            'label' => 'product_variant.form.label.code',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('price', CurrencyType::class, array(
            'label' => 'product.form.label.price',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('reducedPrice', CurrencyType::class, array(
            'label' => 'product_variant.form.label.reducedPrice',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('reduced', BooleanType::class, array(
            'label' => 'product_variant.form.label.reduced',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingCategory', ShippingCategoryChoiceType::class, array(
            'label' => 'product_variant.form.label.shipping_category',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingRequired', BooleanType::class, array(
            'label' => 'product_variant.form.label.shipping_required',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('stock', IntegerType::class, array(
            'label' => 'product_variant.form.label.stock',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('stockTracked', BooleanType::class, array(
            'label' => 'product_variant.form.label.stock_tracked',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('width', TextType::class, array(
            'label' => 'product_variant.form.label.width',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('height', TextType::class, array(
            'label' => 'product_variant.form.label.height',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('depth', TextType::class, array(
            'label' => 'product_variant.form.label.depth',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('weight', TextType::class, array(
            'label' => 'product_variant.form.label.weight',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('volume', TextType::class, array(
            'label' => 'product_variant.form.label.volume',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('taxCategory', EntityType::class, array(
            'class' => $this->taxCategoryClass,
            'choice_label' => 'name',
            'label' => 'product.form.label.taxCategory',
            'translation_domain' => 'EnhavoShopBundle',
            'placeholder' => '---',
        ));

        $builder->add('position', IntegerType::class);
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
