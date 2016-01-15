<?php

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'label.title'
        ));
        $builder->add('public', 'enhavo_boolean');
        $builder->add('order', 'hidden', array(
            'attr' => array('class' => 'order')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\PageBundle\Entity\Tags'
        ));
    }

    public function getName()
    {
        return 'enhavo_page_tag';
    }
}