<?php

/**
 * ProductOptionType.php
 *
 * @since 16/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductOptionValueTranslationType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductOptionValueType extends AbstractType
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
        $builder
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ProductOptionValueTranslationType::class,
                'label' => 'sylius.form.option.name',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
        ;

        $builder->add('position', PositionType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }
}
