<?php

namespace esperanto\ProjectBundle\Form\Type;

use esperanto\PageBundle\Form\Type\PageType as BasePageType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends BasePageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}