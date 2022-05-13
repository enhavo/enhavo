<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    private string $dataClass;

    public function __construct(string $dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();
            $payment = $event->getData();

            $form->add('method', PaymentMethodChoiceType::class, [
                'label' => 'sylius.form.checkout.payment_method',
                'subject' => $payment,
                'expanded' => true,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_checkout_payment';
    }
}
