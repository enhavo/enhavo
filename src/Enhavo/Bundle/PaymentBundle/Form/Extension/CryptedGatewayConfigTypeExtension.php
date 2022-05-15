<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Extension;

use Payum\Core\Security\CryptedInterface;
use Payum\Core\Security\CypherInterface;
use Sylius\Bundle\PayumBundle\Form\Type\GatewayConfigType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class CryptedGatewayConfigTypeExtension extends AbstractTypeExtension
{
    private ?CypherInterface $cypher;

    public function __construct(?CypherInterface $cypher = null)
    {
        $this->cypher = $cypher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null === $this->cypher) {
            return;
        }

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $gatewayConfig = $event->getData();

                if (!$gatewayConfig instanceof CryptedInterface) {
                    return;
                }

                $gatewayConfig->decrypt($this->cypher);

                $event->setData($gatewayConfig);
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $gatewayConfig = $event->getData();

                if (!$gatewayConfig instanceof CryptedInterface) {
                    return;
                }

                $gatewayConfig->encrypt($this->cypher);

                $event->setData($gatewayConfig);
            })
        ;
    }

    public function getExtendedType(): string
    {
        return GatewayConfigType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [GatewayConfigType::class];
    }
}
