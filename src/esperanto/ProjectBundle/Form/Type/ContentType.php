<?php

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use esperanto\PageBundle\Form\Type\PageType;
use Symfony\Component\Form\FormEvent;


class ContentType extends PageType
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // parent::buildForm($builder, $options);

        $builder->add('citation', 'textarea', array(
            'label' => 'form.label.citation'
        ));

        $builder->add('author', 'textarea', array(
            'label' => 'form.label.author'
        ));

        $builder->add('text', 'wysiwyg', array(
            'label' => 'form.label.text'
        ));

        $builder->add('picture', 'esperanto_files', array(
            'label' => 'form.label.picture'
        ));

        $builder->add('heading', 'text', array(
            'label' => 'form.label.heading'
        ));

        $builder->add('name1', 'text', array(
            'label' => 'form.label.name1'
        ));

        $builder->add('street1', 'text', array(
            'label' => 'form.label.street1'
        ));

        $builder->add('place1', 'text', array(
            'label' => 'form.label.place1'
        ));

        $builder->add('phone1', 'text', array(
            'label' => 'form.label.phone1'
        ));

        $builder->add('fax1', 'text', array(
            'label' => 'form.label.fax1'
        ));

        $builder->add('mail1', 'text', array(
            'label' => 'form.label.mail1'
        ));

        $builder->add('name2', 'text', array(
            'label' => 'form.label.name2'
        ));

        $builder->add('street2', 'text', array(
            'label' => 'form.label.street2'
        ));

        $builder->add('place2', 'text', array(
            'label' => 'form.label.place2'
        ));

        $builder->add('phone2', 'text', array(
            'label' => 'form.label.phone2'
        ));

        $builder->add('fax2', 'text', array(
            'label' => 'form.label.fax2'
        ));

        $builder->add('mail2', 'text', array(
            'label' => 'form.label.mail2'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ProjectBundle\Entity\Content'
        ));
    }
}