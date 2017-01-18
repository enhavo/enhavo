<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;
    
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ) );

        $builder->add('subject', 'text', array(
            'label' => 'newsletter.form.label.subject',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ) );

        $builder->add('text', 'enhavo_wysiwyg', array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle'
        ) );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_newsletter_newsletter';
    }
}