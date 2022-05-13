<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SelectPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('payments', ChangePaymentMethodType::class, [
            'entry_type' => PaymentType::class,
            'label' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_checkout_select_payment';
    }
}
