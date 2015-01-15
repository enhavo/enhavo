<?php

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;


class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'form.label.name'
        ));

        $builder->add('email', 'text', array(
            'label' => 'form.label.email'
        ));

        $builder->add('subject', 'text', array(
            'label' => 'form.label.subject'
        ));

        $builder->add('message', 'textarea', array(
            'label' => 'form.label.message'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ProjectBundle\Model\Email'
        ));
    }

    public function getName()
    {
        return 'esperanto_project_email';
    }
}