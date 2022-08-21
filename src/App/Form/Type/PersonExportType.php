<?php

namespace App\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('from', DateType::class, []);
        $builder->add('to', DateType::class, []);
    }
}
