<?php

namespace esperanto\ProjectBundle\Form\Type;

use esperanto\ReferenceBundle\Form\Type\ReferenceType as BaseReferenceType;
use Symfony\Component\Form\FormBuilderInterface;

class ReferenceType extends BaseReferenceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('content', 'esperanto_content', array(
            'label' => 'form.label.content'
        ));

        $builder->add('images', 'esperanto_files', array(
            'label' => 'form.label.homepage_picture'
        ));

        $builder->add('preview_picture', 'esperanto_files', array(
            'label' => 'form.label.preview_picture'
        ));
    }
}