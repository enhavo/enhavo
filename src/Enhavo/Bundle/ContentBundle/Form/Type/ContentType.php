<?php

namespace Enhavo\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('meta_description', 'textarea', array(
            'label' => 'form.label.meta_description',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('page_title', 'text', array(
            'label' => 'article.form.label.page_title',
            'translation_domain' => 'EnhavoArticleBundle'
        ));


        $builder->add('public', 'enhavo_boolean', array(
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('priority', 'choice', array(
            'label' => 'article.form.label.priority',
            'translation_domain' => 'EnhavoArticleBundle',
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
            'label' => 'article.form.label.change_frequency',
            'translation_domain' => 'EnhavoArticleBundle',
            'choices'   => array(
                'always' => 'article.label.always',
                'hourly' => 'article.label.hourly',
                'daily' => 'article.label.daily',
                'weekly' => 'article.label.weekly',
                'monthly' => 'article.label.monthly',
                'yearly' => 'article.label.yearly',
                'never' => 'article.label.never',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('publication_date', 'enhavo_datetime', array(
            'label' => 'article.form.label.publication_date',
            'translation_domain' => 'EnhavoArticleBundle'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'routing_strategy' => 'id',
            'routing_route' => null
        ));
    }

    public function getName()
    {
        return 'enhavo_content_content';
    }

    public function getParent()
    {
        return 'enhavo_routing';
    }
}