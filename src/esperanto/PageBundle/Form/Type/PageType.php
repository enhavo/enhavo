<?php

namespace esperanto\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PageType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $route;

    public function __construct($dataClass, $route, Router $router)
    {
        $this->route = $route;
        $this->dataClass = $dataClass;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->router;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($router) {
            $page = $event->getData();
            $form = $event->getForm();

            if (!empty($page) && $page->getId() && !empty($route)) {
                $url = $router->generate($this->route, array(
                    'id' => $page->getId(),
                    'slug' => $page->getSlug(),
                ), true);

                $form->add('link', 'text', array(
                    'mapped' => false,
                    'data' => $url,
                    'disabled' => true
                ));
            }
        });

        $builder->add('title', 'text', array(
            'label' => 'form.label.title.h1'
        ));

        $builder->add('slug', 'text', array(
            'label' => 'form.label.slug'
        ));

        $builder->add('meta_description', 'textarea', array(
            'label' => 'form.label.meta_description'
        ));

        $builder->add('page_title', 'text', array(
            'label' => 'form.label.page_title'
        ));

        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser'
        ));

        $builder->add('social_media', 'choice', array(
            'label' => 'form.label.social_media',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('public', 'choice', array(
            'label' => 'form.label.public',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('priority', 'choice', array(
            'label' => 'form.label.priority',
            'choices'   => array(
                '0.1' => '1',
                '0.2' => '2',
                '0.3' => '3',
                '0.4' => '4',
                '0.5' => '5',
                '0.6' => '6',
                '0.7' => '7',
                '0.8' => '8',
                '0.9' => '9',
                '1' => '10'
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('change_frequency', 'choice', array(
            'label' => 'form.label.change_frequency',
            'choices'   => array(
                'always' => 'Immer',
                'hourly' => 'Stündlich',
                'daily' => 'Täglich',
                'weekly' => 'Wöchentlich',
                'monthly' => 'Monatlich',
                'yearly' => 'Jährlich',
                'never' => 'Nie',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('picture', 'esperanto_files', array(
            'label' => 'form.label.picture'
        ));

        $builder->add('content', 'esperanto_content', array(
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'esperanto_page_page';
    }
}