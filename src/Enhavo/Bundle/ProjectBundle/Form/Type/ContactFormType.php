<?php

namespace Enhavo\Bundle\ProjectBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType as BaseType;

class ContactFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('firstName', TextType::class);
        $builder->add('lastName', TextType::class);
        $builder->add('message', TextareaType::class);
    }
}
