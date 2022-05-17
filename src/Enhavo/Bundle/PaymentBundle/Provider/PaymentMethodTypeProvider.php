<?php

namespace Enhavo\Bundle\PaymentBundle\Provider;

class PaymentMethodTypeProvider
{
    /** @var array<PaymentMethodType> */
    private array $methods = [];

    public function __construct(array $methods)
    {
        foreach ($methods as $key => $method) {
            if ($method['enabled']) {
                $this->methods[$key] = new PaymentMethodType(
                    $key,
                    $method['form'],
                    $method['gateway_factory'],
                    $method['label'],
                    $method['translation_domain'],
                );
            }

        }
    }

    /**
     * @return array<PaymentMethodType>
     */
    public function provide(): array
    {
        return $this->methods;
    }

    public function getType($name): PaymentMethodType
    {
        if (isset($this->methods[$name])) {
            return $this->methods[$name];
        }
        throw new \InvalidArgumentException(sprintf('Payment method type with name "%s" not exists', $name));
    }
}

