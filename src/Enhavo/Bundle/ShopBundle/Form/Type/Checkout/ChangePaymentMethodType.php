<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Sylius\Component\Payment\Model\PaymentInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ChangePaymentMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
            $payments = $event->getData();
            $form = $event->getForm();

            foreach ($payments as $key => $payment) {
                if (!in_array($payment->getState(), [PaymentInterface::STATE_NEW, PaymentInterface::STATE_CART], true)) {
                    $form->remove((string) $key);
                }
            }
        });
    }

    public function getParent(): string
    {
        return CollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_change_payment_method';
    }
}
