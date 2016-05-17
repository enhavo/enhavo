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
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var bool
     */
    protected $dynamicRouting;

    public function __construct($dataClass, $dynamicRouting, $route, RouterInterface $router)
    {
        $this->route = $route;
        $this->dataClass = $dataClass;
        $this->router = $router;
        $this->dynamicRouting = $dynamicRouting;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->router;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($router) {
            $article = $event->getData();
            $form = $event->getForm();

            if (!empty($article) && $article->getId() && !empty($route)) {
                $url = $router->generate($this->route, array(
                    'id' => $article->getId(),
                    'slug' => $article->getSlug(),
                ), true);

                $form->add('link', 'text', array(
                    'mapped' => false,
                    'data' => $url,
                    'disabled' => true
                ));
            }
        });

        if($this->dynamicRouting) {
            $builder->add('route', 'enhavo_route');
        }

        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('slug', 'hidden');

        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('dateFrom', 'datetime', array(
            'label' => 'appointment.form.label.dateFrom',
            'translation_domain' => 'EnhavoCalendarBundle',
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
        ));

        $builder->add('dateTo', 'datetime', array(
            'label' => 'appointment.form.label.dateTo',
            'translation_domain' => 'EnhavoCalendarBundle',
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
        ));

        $builder->add('picture', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('grid', 'enhavo_grid');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_calendar_appointment';
    }
}