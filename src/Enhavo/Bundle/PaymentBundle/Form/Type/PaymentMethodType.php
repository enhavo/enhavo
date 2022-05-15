<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class PaymentMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('translations');
        $builder->add('name', TextType::class);
        $builder->add('description', TextareaType::class);
        $builder->add('instructions', TextareaType::class);
        $builder->add('gatewayConfig', GatewayConfigType::class);
    }

    public function getParent()
    {
        return \Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodType::class;
    }
}
