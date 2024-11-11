<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 17:22
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Mock\Form;

use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextBlockMockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', BlockNodeType::class, [
            'label' => 'Text',
            'prototype' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Text::class]);
    }
}
