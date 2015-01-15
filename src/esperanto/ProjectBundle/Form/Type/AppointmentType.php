<?php

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;


class AppointmentType extends AbstractType
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('text', 'wysiwyg', array(
            'label' => 'form.label.text'
        ));

        $builder->add('date', 'datetime', array(
            'label' => 'form.label.date',
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ProjectBundle\Entity\Appointment'
        ));
    }

    public function getName()
    {
        return 'esperanto_project_appointment';
    }
}