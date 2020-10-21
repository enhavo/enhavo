<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, array(
            'label' => 'subscriber.form.label.email',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => null,
            'subscription' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_subscriber';
    }
}
