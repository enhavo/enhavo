<?php

namespace Enhavo\Bundle\ShopBundle\Shipping;

use Enhavo\Bundle\ShopBundle\Model\ShippingMethodInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface as ShippingCalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface as TaxCalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;

class DelegatingCalculator implements DelegatingCalculatorInterface
{
    public function __construct(
        private ServiceRegistryInterface $registry,
        private TaxRateResolverInterface $taxRateResolver,
        private TaxCalculatorInterface $taxCalculator,
    )
    {
    }

    public function calculate(ShipmentInterface $subject): int
    {
        $method = $this->getMethod($subject);
        /** @var ShippingCalculatorInterface $shippingCalculator */
        $shippingCalculator = $this->registry->get($method->getCalculator());
        $taxRate = $this->taxRateResolver->resolve($method);

        $price = $shippingCalculator->calculate($subject, $method->getConfiguration());

        if ($taxRate === null || !$taxRate->isIncludedInPrice()) {
            return $price;
        }

        return $price - $this->taxCalculator->calculate($price, $taxRate);
    }

    public function calculateTax($price, ShipmentInterface $subject): int
    {
        $method = $this->getMethod($subject);
        $taxRate = $this->taxRateResolver->resolve($method);
        if ($taxRate === null) {
            return 0;
        }

        return $this->taxCalculator->calculate($price, $taxRate);
    }

    private function getMethod(ShipmentInterface $subject): ShippingMethodInterface
    {
        if (null === $method = $subject->getMethod()) {
            throw new UndefinedShippingMethodException('Cannot calculate charge for shipment without a defined shipping method.');
        }
        return $method;
    }
}
