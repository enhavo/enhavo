<?php

namespace esperanto\NewsletterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');

        $builder->add('subject', 'text');

        $builder->add('text', 'wysiwyg');

        $builder->add('sent', 'checkbox', array(
            'read_only' => true
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\NewsletterBundle\Entity\Newsletter'
        ));
    }

    public function getName()
    {
        return 'esperanto_newsletter_newsletter';
    }
}