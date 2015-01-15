<?php

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;


class AppointmentMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('date', 'text', array(
            'label' => 'form.label.date'
        ));

        $builder->add('name', 'text', array(
            'label' => 'form.label.name'
        ));

        $builder->add('email', 'text', array(
            'label' => 'form.label.email'
        ));

        $builder->add('age', 'integer', array(
            'label' => 'form.label.age'
        ));

        $builder->add('occupation', 'text', array(
            'label' => 'form.label.occupation'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ProjectBundle\Model\AppointmentMail'
        ));
    }

    public function getName()
    {
        return 'esperanto_project_appointmentMail';
    }
}