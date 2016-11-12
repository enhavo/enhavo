<?php

namespace Enhavo\Bundle\ThemeBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType as BaseType;

class ContactFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('firstName', 'text');
        $builder->add('lastName', 'text');
        $builder->add('message', 'textarea');
    }
}
