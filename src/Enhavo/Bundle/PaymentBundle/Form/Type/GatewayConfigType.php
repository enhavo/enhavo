<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Type;

use Enhavo\Bundle\PaymentBundle\Entity\GatewayConfig;
use Enhavo\Bundle\PaymentBundle\Provider\PaymentMethodTypeProvider;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class GatewayConfigType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        private PaymentMethodTypeProvider $provider,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof GatewayConfig) {
                $formType = $this->provider->getType($data->getGatewayType())->getForm();
                $form->add('config', $formType);
            }
        });
    }

    public function getBlockPrefix(): string
    {
        return 'enhavo_payment_gateway_config';
    }
}
