<?php

/**
 * ProductType.php
 *
 * @since 06/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Enhavo\Bundle\ShopBundle\Entity\Product;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoCompleteChoiceType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductType extends AbstractType
{
    /** @var int */
    private $productId;

    public function __construct(
        private string $dataClass,
        private string $taxCategoryClass,
        private string $optionClass,
        private EventSubscriberInterface $generateProductVariants
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var integer $id */
                $product = $event->getData();
                if ($product instanceof Product) {
                    $this->productId = $product->getId();
                }
            })
        ;
        $builder->add('enabled', BooleanType::class, array(
            'label' => 'product.form.label.active',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('title', TextType::class, array(
            'label' => 'product.form.label.title',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('description', WysiwygType::class, array(
            'label' => 'product.form.label.description',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'product.form.label.picture',
            'translation_domain' => 'EnhavoShopBundle',
            'multiple' => false
        ));

        $builder->add('pictures', MediaType::class, array(
            'multiple' => true,
            'label' => 'product.form.label.pictures',
            'translation_domain' => 'EnhavoShopBundle',
            'required' => false
        ));

        $builder->add('code', TextType::class, array(
            'label' => 'product.form.label.code',
            'translation_domain' => 'EnhavoShopBundle',
            'attr' => array(
                'readonly' => true,
            ),
        ));

        $builder->add('price', CurrencyType::class, array(
            'label' => 'product.form.label.price',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('reducedPrice', CurrencyType::class, array(
            'label' => 'product.form.label.reducedPrice',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('reduced', BooleanType::class, array(
            'label' => 'product.form.label.reduced',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingCategory', ShippingCategoryChoiceType::class, array(
            'label' => 'product.form.label.shippingCategory',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('shippingRequired', BooleanType::class, array(
            'label' => 'product.form.label.shippingRequired',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('lengthUnit', ChoiceType::class, array(
            'label' => 'product.form.label.lengthUnit',
            'translation_domain' => 'EnhavoShopBundle',
            'choices' => [
                'mm' => 'mm',
                'cm' => 'cm',
                'm' => 'm',
            ]
        ));

        $builder->add('width', TextType::class, array(
            'label' => 'product.form.label.width',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('height', TextType::class, array(
            'label' => 'product.form.label.height',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('depth', TextType::class, array(
            'label' => 'product.form.label.depth',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('weightUnit', ChoiceType::class, array(
            'label' => 'product.form.label.weightUnit',
            'translation_domain' => 'EnhavoShopBundle',
            'choices' => [
                'mg' => 'mg',
                'g' => 'g',
                'kg' => 'kg',
                't' => 't'
            ]
        ));

        $builder->add('weight', TextType::class, array(
            'label' => 'product.form.label.weight',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('volumeUnit', ChoiceType::class, array(
            'label' => 'product.form.label.volumeUnit',
            'translation_domain' => 'EnhavoShopBundle',
            'choices' => [
                'ml' => 'ml',
                'l' => 'l',
            ]
        ));

        $builder->add('volume', TextType::class, array(
            'label' => 'product.form.label.volume',
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('route', RouteType::class);

        $builder->add('taxCategory', EntityType::class, array(
            'class' => $this->taxCategoryClass,
            'choice_label' => 'name',
            'label' => 'product.form.label.taxCategory',
            'translation_domain' => 'EnhavoShopBundle',
            'placeholder' => '---',
        ));

        $builder->add('options', EntityType::class, array(
            'class' => $this->optionClass,
            'choice_label' => 'translation.name',
            'label' => 'product.label.options',
            'multiple' => true,
            'translation_domain' => 'EnhavoShopBundle',
        ));

        $builder->add('attributes', ListType::class, [
            'entry_type' => ProductAttributeValueType::class,
            'required' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'product.form.label.attributes',
            'translation_domain' => 'EnhavoShopBundle'
        ]);

        $builder->add('associations', ListType::class, [
            'entry_type' => ProductAssociationType::class,
            'required' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'product.form.label.associations',
            'translation_domain' => 'EnhavoShopBundle',
            'entry_options' => [
                'productId' => $this->productId
            ]
        ]);

        $builder->add('categories', TermTreeChoiceType::class, [
            'multiple' => true,
            'taxonomy' => 'shop_category'
        ]);

        $builder->add('tags', TermAutoCompleteChoiceType::class, [
            'multiple' => true,
            'route' => 'enhavo_shop_tag_auto_complete',
            'translation_domain' => 'EnhavoShopBundle',
            'create_route' => 'enhavo_shop_tag_create',
            'edit_route' => 'enhavo_shop_tag_update',
            'frame_key' => 'shop_tags'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }
}
