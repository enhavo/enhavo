<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', CurrencyType::class, [

        ]);

        $builder->add('currencyCode', CurrencyChoiceType::class, [

        ]);

        $builder->add('method', PaymentMethodChoiceType::class, [

        ]);

        $builder->add('state', TextType::class, [
            'disabled' => true
        ]);

        $builder->add('token', TextType::class, [
            'disabled' => true
        ]);
    }
}
