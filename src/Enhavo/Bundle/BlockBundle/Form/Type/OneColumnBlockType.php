<?php

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\OneColumnBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('column', BlockNodeType::class, [
            'label' => 'column.label.column',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content'],
            'prototype' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OneColumnBlock::class
        ]);
    }

    public function getParent()
    {
        return ColumnType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_one_column';
    }
}
