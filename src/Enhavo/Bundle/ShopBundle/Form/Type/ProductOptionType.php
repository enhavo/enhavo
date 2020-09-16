<?php

/**
 * ProductOptionType.php
 *
 * @since 16/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\ShopBundle\Form\Type\ProductOptionValueType;

class ProductOptionType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class);
        $builder->add('position', IntegerType::class);
        $builder->add('name', TextType::class);
        $builder->add('values', ListType::class, [
            'entry_type' => ProductOptionValueType::class,
            'sortable' => true,
            'border' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_product_option';
    }
}
