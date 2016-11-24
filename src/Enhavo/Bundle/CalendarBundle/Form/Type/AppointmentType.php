<?php

namespace Enhavo\Bundle\CalendarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;

class AppointmentType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var bool
     */
    protected $routingStrategy;

    public function __construct($dataClass, $routingStrategy, $route)
    {
        $this->dataClass = $dataClass;
        $this->route = $route;
        $this->routingStrategy = $routingStrategy;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => true
        ));

        $builder->add('dateFrom', 'enhavo_datetime', array(
            'label' => 'appointment.form.label.dateFrom',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('dateTo', 'enhavo_datetime', array(
            'label' => 'appointment.form.label.dateTo',
            'translation_domain' => 'EnhavoCalendarBundle',
        ));

        $builder->add('picture', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('grid', 'enhavo_grid', array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass,
            'routing_strategy' => $this->routingStrategy,
            'routing_route' => $this->route
        ));
    }

    public function getParent()
    {
        return 'enhavo_content_content';
    }

    public function getName()
    {
        return 'enhavo_calendar_appointment';
    }
}