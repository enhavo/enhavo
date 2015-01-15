<?php

namespace esperanto\NewsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Routing\Router;

class NewsType extends AbstractType
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title.h1'
        ));

        $builder->add('meta_description', 'textarea', array(
            'label' => 'form.label.meta_description'
        ));

        $builder->add('page_title', 'text', array(
            'label' => 'form.label.page_title'
        ));

        $builder->add('slug', 'text', array(
            'label' => 'form.label.slug'
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

        $builder->add('teaser', 'textarea', array(
            'label' => 'form.label.teaser'
        ));

        $builder->add('publication_date', 'datetime', array(
            'label' => 'form.label.publication_date',
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
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
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'esperanto\NewsBundle\Entity\News'
        ));
    }

    public function getName()
    {
        return 'esperanto_news_news';
    }
}