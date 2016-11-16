<?php

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
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
     * @var boolean
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

        $builder->add('picture', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('grid', 'enhavo_grid', array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('categories', 'enhavo_category', array());
    }

    public function configureOptions(OptionsResolver $resolver)
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
        return 'enhavo_article_article';
    }
}