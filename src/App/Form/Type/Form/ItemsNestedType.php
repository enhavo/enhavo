<?php

namespace App\Form\Type\Form;

use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemsNestedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, []);
        $builder->add('children', ListType::class, [
            'entry_type' => ItemsNestedType::class,
            'prototype' => false,
        ]);
        $builder->add('position', PositionType::class, []);
    }
}
