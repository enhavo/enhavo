<?php

namespace esperanto\ProjectBundle\Form\Type;

use esperanto\PageBundle\Form\Type\PageType as BasePageType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends BasePageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('picture', 'esperanto_files', array(
            'information' => array('Bilder in der Größe 960x360 Pixel (oder ein vielfaches davon) hochladen'),
            'label' => 'form.label.picture'
        ));

        $builder->add('content', 'esperanto_content', array(

        ));
    }
}