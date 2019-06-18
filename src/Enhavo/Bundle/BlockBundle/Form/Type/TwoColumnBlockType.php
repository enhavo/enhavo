<?php

namespace Enhavo\Bundle\BlockBundle\Form\Type;


use Enhavo\Bundle\BlockBundle\Model\Column\TwoColumnBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwoColumnBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', ContainerType::class, [
            'label' => 'column.label.column_one',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content']
        ]);

        $builder->add('columnTwo', ContainerType::class, [
            'label' => 'column.label.column_two',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TwoColumnBlock::class
        ));
    }

    public function getParent()
    {
        return ColumnType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_two_column';
    }
}
