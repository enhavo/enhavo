<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\ThreeColumnBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', BlockNodeType::class, [
            'label' => 'column.label.column_one',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content'],
            'prototype' => false,
        ]);

        $builder->add('columnTwo', BlockNodeType::class, [
            'label' => 'column.label.column_two',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content'],
            'prototype' => false,
        ]);

        $builder->add('columnThree', BlockNodeType::class, [
            'label' => 'column.label.column_three',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content'],
            'prototype' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ThreeColumnBlock::class,
        ]);
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
