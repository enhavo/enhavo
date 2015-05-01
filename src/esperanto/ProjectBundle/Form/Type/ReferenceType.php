<?php

namespace esperanto\ProjectBundle\Form\Type;

use esperanto\ReferenceBundle\Form\Type\ReferenceType as BaseReferenceType;
use Symfony\Component\Form\FormBuilderInterface;

class ReferenceType extends BaseReferenceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}