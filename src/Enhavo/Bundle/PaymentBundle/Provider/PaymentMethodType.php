<?php

namespace Enhavo\Bundle\PaymentBundle\Provider;

class PaymentMethodType
{
    public function __construct(
        private string $type,
        private string $form,
        private string $gatewayFactory,
        private string $label,
        private ?string $translationDomain = null,
    )
    {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getForm(): string
    {
        return $this->form;
    }

    public function getGatewayFactory(): string
    {
        return $this->gatewayFactory;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }
}
