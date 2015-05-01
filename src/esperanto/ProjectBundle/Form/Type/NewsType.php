<?php

namespace esperanto\ProjectBundle\Form\Type;

use esperanto\NewsBundle\Form\Type\NewsType as BaseNewsType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsType extends BaseNewsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}