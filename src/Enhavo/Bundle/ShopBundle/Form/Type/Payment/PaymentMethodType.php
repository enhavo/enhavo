<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Payment;

use Enhavo\Bundle\ShopBundle\Entity\PaymentMethod;
use Enhavo\Bundle\ShopBundle\Form\Type\GatewayConfig\PayPalType;
use Enhavo\Bundle\ShopBundle\Payment\Provider\PaymentMethodTypeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PaymentMethodType extends AbstractType
{
    public function __construct(
        private PaymentMethodTypeProvider $provider,
    )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('translations');
        $builder->add('gatewayName', TextType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof PaymentMethod) {
                $formType = $this->provider->getType($data->getGatewayType())->getForm();
                $form->add('config', $formType);
            }
        });
    }

    public function getParent()
    {
        return \Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodType::class;
    }
}
