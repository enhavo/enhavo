<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalSubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, array(
            'label' => 'subscriber.form.label.email',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ));

    }

    public function resolveOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LocalSubscriber::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_local_subscriber';
    }
}
