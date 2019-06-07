<?php

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\ThreeColumnBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', ContainerType::class, [
            'label' => 'column.label.column_one',
            'translation_domain' => 'EnhavoBlockBundle',
            'block_groups' => ['content']
        ]);

        $builder->add('columnTwo', ContainerType::class, [
            'label' => 'column.label.column_two',
            'translation_domain' => 'EnhavoBlockBundle',
            'block_groups' => ['content']
        ]);

        $builder->add('columnThree', ContainerType::class, [
            'label' => 'column.label.column_three',
            'translation_domain' => 'EnhavoBlockBundle',
            'block_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ThreeColumnBlock::class
        ));
    }

    public function getParent()
    {
        return ColumnType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_three_column';
    }
}
