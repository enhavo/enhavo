<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\NewsletterBundle\Entity\SubscribeBlock'
        ));
    }

    public function getName()
    {
        return 'enhavo_newsletter_subscribe_block';
    }
}
