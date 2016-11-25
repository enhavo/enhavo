<?php

/**
 * ProductType.php
 *
 * @since 06/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /**
     * @var boolean
     */
    protected $translation;

    public function __construct($dataClass, $taxRateClass, $optionClass, $translation)
    {
        $this->dataClass = $dataClass;
        $this->taxRateClass = $taxRateClass;
        $this->optionClass = $optionClass;
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'label.title',
            'translation' => $this->translation
        ));

        $builder->add('picture', 'enhavo_files', array(
            'label' => 'label.picture',
            'multiple' => false
        ));

        $builder->add('code', 'text', array(
            'label' => 'label.code',
        ));

        $builder->add('price', 'text', array(
            'label' => 'label.price'
        ));

        $builder->add('shippingCategory', 'sylius_shipping_category_choice', array(
            'label' => 'label.shippingCategory'
        ));

        $builder->add('width', 'text', array(
            'label' => 'label.width'
        ));

        $builder->add('height', 'text', array(
            'label' => 'label.height'
        ));

        $builder->add('depth', 'text', array(
            'label' => 'label.depth'
        ));

        $builder->add('weight', 'text', array(
            'label' => 'label.weight'
        ));

        $builder->add('volume', 'text', array(
            'label' => 'label.volume'
        ));

        $builder->add('volume', 'text', array(
            'label' => 'label.volume'
        ));

        $builder->add('taxRate', 'entity', array(
            'class' => $this->taxRateClass,
            'choice_label' => 'name',
            'label' => 'label.taxRate'
        ));

        $builder->add('options', 'entity', array(
            'class' => $this->optionClass,
            'choice_label' => 'code',
            'label' => 'label.options',
            'multiple' => true,
            'expanded' => true
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_shop_product';
    }
}