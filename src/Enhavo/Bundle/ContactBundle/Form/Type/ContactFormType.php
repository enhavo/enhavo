<?php

namespace Enhavo\Bundle\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text');
        $builder->add('message', 'text');
    }


    public function getName()
    {
        return 'enhavo_contact_contact';
    }
}
