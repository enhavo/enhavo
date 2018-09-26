<?php

namespace Enhavo\Bundle\ContentBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\SlugType;
use Enhavo\Bundle\ContentBundle\EventListener\RouterSubscriber;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouterType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    /**
     * @var string
     */
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation,
            'help' => 'Das ist Hilfetext'
        ));

        $builder->add('meta_description', 'textarea', array(
            'label' => 'form.label.meta_description',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('page_title', 'text', array(
            'label' => 'form.label.page_title',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('public', 'enhavo_boolean', array(
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('priority', 'choice', array(
            'label' => 'form.label.priority',
            'translation_domain' => 'EnhavoContentBundle',
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
            'translation_domain' => 'EnhavoContentBundle',
            'choices'   => array(
                'always' => 'form.label.always',
                'hourly' => 'form.label.hourly',
                'daily' => 'form.label.daily',
                'weekly' => 'form.label.weekly',
                'monthly' => 'form.label.monthly',
                'yearly' => 'form.label.yearly',
                'never' => 'form.label.never',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('noIndex', 'enhavo_boolean', array(
            'label' => 'form.label.no_index',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('noFollow', 'enhavo_boolean', array(
            'label' => 'form.label.no_follow',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('publication_date', 'enhavo_datetime', array(
            'label' => 'form.label.publication_date',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('published_until', 'enhavo_datetime', array(
            'label' => 'form.label.published_until',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('openGraphTitle', 'text', array(
            'label' => 'form.label.openGraphTitle',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('openGraphDescription', 'text', array(
            'label' => 'form.label.openGraphDescription',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('openGraphImage', 'enhavo_files', array(
            'label' => 'form.label.openGraphImage',
            'translation_domain' => 'EnhavoContentBundle',
            'multiple' => false
        ));

        if($options['slugable']) {
            $builder->add('slug', SlugType::class, []);
        }

        if($options['routable']) {
            $builder->add('route', RouteType::class, []);
        }

        if($options['router']) {
            $builder->add('router_route', RouterType::class, []);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'slugable' => false,
            'routable' => false,
            'router' => false
        ));
    }

    public function getName()
    {
        return 'enhavo_content_content';
    }
}