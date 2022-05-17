<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Type\Gateway;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class StripeGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishable_key', TextType::class, [
                'label' => 'sylius.form.gateway_configuration.stripe.publishable_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'sylius.gateway_config.stripe.publishable_key.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('secret_key', TextType::class, [
                'label' => 'sylius.form.gateway_configuration.stripe.secret_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'sylius.gateway_config.stripe.secret_key.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
        ;
    }
}
