<?php

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PageType extends AbstractType
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

        if($this->dynamicRouting) {
            $builder->add('route', 'enhavo_route');
        }

        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('slug', 'text', array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('meta_description', 'textarea', array(
            'label' => 'form.label.meta_description',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('page_title', 'text', array(
            'label' => 'page.form.label.page_title',
            'translation_domain' => 'EnhavoPageBundle'
        ));

        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('social_media', 'enhavo_boolean', array(
        'label' => 'page.form.label.social_media',
        'translation_domain' => 'EnhavoPageBundle'
    ));

        $builder->add('public', 'enhavo_boolean');

        $builder->add('priority', 'choice', array(
            'label' => 'page.form.label.priority',
            'translation_domain' => 'EnhavoPageBundle',
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
            'label' => 'page.form.label.change_frequency',
            'translation_domain' => 'EnhavoPageBundle',
            'choices'   => array(
                'always' => 'page.label.always',
                'hourly' => 'page.label.hourly',
                'daily' => 'page.label.daily',
                'weekly' => 'page.label.weekly',
                'monthly' => 'page.label.monthly',
                'yearly' => 'page.label.yearly',
                'never' => 'page.label.never',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('picture', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('content', 'enhavo_grid', array(
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
        return 'enhavo_page_page';
    }
}