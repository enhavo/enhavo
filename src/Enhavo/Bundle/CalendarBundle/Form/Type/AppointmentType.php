<?php

namespace Enhavo\Bundle\CalendarBundle\Form\Type;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /**
     * @var string
     */
    protected $translation;

    public function __construct($dataClass, $routingStrategy, $route, $translation)
    {
        $this->dataClass = $dataClass;
        $this->route = $route;
        $this->routingStrategy = $routingStrategy;
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('dateFrom', 'enhavo_datetime', array(
            'label' => 'appointment.form.label.dateFrom',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('dateTo', 'enhavo_datetime', array(
            'label' => 'appointment.form.label.dateTo',
            'translation_domain' => 'EnhavoCalendarBundle',
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('grid', 'enhavo_grid', array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('externalId', 'text', array(
            'label' => 'appointment.form.label.externalId',
            'translation_domain' => 'EnhavoCalendarBundle',
            'read_only' => true
        ));

        $builder->add('locationLongitude', 'text', array(
            'label' => 'appointment.form.label.locationLongitude',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('locationLatitude', 'text', array(
            'label' => 'appointment.form.label.locationLatitude',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('locationName', 'text', array(
            'label' => 'appointment.form.label.locationName',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('locationCity', 'text', array(
            'label' => 'appointment.form.label.locationCity',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('locationCountry', 'text', array(
            'label' => 'appointment.form.label.locationCountry',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('locationStreet', 'text', array(
            'label' => 'appointment.form.label.locationStreet',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('locationZip', 'text', array(
            'label' => 'appointment.form.label.locationZip',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('repeatRule', 'text', array(
            'label' => 'appointment.form.label.repeatRule',
            'translation_domain' => 'EnhavoCalendarBundle'
        ));

        $builder->add('importerName', 'text', array(
            'label' => 'appointment.form.label.importerName',
            'translation_domain' => 'EnhavoCalendarBundle',
            'read_only' => true
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass,
            'slugable' => true
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