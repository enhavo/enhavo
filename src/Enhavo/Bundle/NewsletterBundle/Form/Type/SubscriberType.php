<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, array(
            'label' => 'subscriber.form.label.email',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ));

        $builder->add('active', BooleanType::class, array(
            'label' => 'subscriber.form.label.active',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ));
    }

    public function resolveOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\NewsletterBundle\Entity\Subscriber'
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_subscriber';
    }
}