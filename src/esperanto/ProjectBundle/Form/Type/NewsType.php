<?php

namespace esperanto\ProjectBundle\Form\Type;

use esperanto\NewsBundle\Form\Type\NewsType as BaseNewsType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsType extends BaseNewsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('picture', 'esperanto_files', array(
            'label' => 'form.label.picture'
        ));

        $builder->add('content', 'esperanto_content', array(

        ));
    }
}