<?php

/**
 * ProductType.php
 *
 * @since 06/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantGenerationType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var string
     */
    private $taxRateClass;

    /**
     * @var string
     */
    private $optionClass;


    /** @var EventSubscriberInterface */
    private $generateProductVariantsSubscriber;

    public function __construct($dataClass, $taxRateClass, $optionClass, $generateProductVariants)
    {
        $this->dataClass = $dataClass;
        $this->taxRateClass = $taxRateClass;
        $this->optionClass = $optionClass;
        $this->generateProductVariantsSubscriber = $generateProductVariants;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'product.label.title',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'product.label.picture',
            'translation_domain' => 'EnhavoShopBundle',
            'multiple' => false
        ));

        $builder->add('code', TextType::class, array(
            'label' => 'product.label.code',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('price', CurrencyType::class, array(
            'label' => 'product.label.price',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingCategory', ShippingCategoryChoiceType::class, array(
            'label' => 'product.label.shippingCategory',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('width', TextType::class, array(
            'label' => 'product.label.width',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('height', TextType::class, array(
            'label' => 'product.label.height',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('depth', TextType::class, array(
            'label' => 'product.label.depth',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('weight', TextType::class, array(
            'label' => 'product.label.weight',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('volume', TextType::class, array(
            'label' => 'product.label.volume',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('route', RouteType::class);

        $builder->add('taxRate', EntityType::class, array(
            'class' => $this->taxRateClass,
            'choice_label' => 'name',
            'label' => 'product.label.taxRate',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('options', EntityType::class, array(
            'class' => $this->optionClass,
            'choice_label' => 'code',
            'label' => 'label.options',
            'multiple' => true,
            'expanded' => true,
            'translation_domain' => 'EnhavoShopBundle',
        ));
        $builder->add('attributes', ListType::class, [
            'entry_type' => ProductAttributeValueType::class,
            'required' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => false,
        ]);
//        $builder->add('variants', ListType::class, [
//            'entry_type' => ProductVariantGenerationType::class,
//            'allow_add' => false,
//            'allow_delete' => true,
//            'by_reference' => false,
//        ]);
//        $builder->addEventSubscriber($this->generateProductVariantsSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }
}
