<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Form\Type\GridType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $builder->add('title', TextType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ) );

        $builder->add('slug', TextType::class, array(
            'label' => 'newsletter.form.label.slug',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ) );

        $builder->add('subject', TextType::class, array(
            'label' => 'newsletter.form.label.subject',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ) );

        $builder->add('grid', GridType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ) );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_newsletter';
    }
}