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

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockNodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('children', BlockCollectionType::class, [
            'item_groups' => $options['item_groups'],
            'items' => $options['items'],
            'add_label' => $options['add_label'],
            'translation_domain' => $options['translation_domain'],
            'prototype' => $options['prototype'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Node::class,
            'label' => 'block.label.container',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => [],
            'items' => [],
            'add_label' => '',
            'prototype' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_node';
    }
}
