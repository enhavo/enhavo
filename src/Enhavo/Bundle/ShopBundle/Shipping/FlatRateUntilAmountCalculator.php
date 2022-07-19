<?php

namespace Enhavo\Bundle\ShopBundle\Shipping;

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

class FlatRateUntilAmountCalculator implements CalculatorInterface
{
    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        /** @var \Enhavo\Bundle\ShopBundle\Model\ShipmentInterface $subject */
        if ($subject->getOrder()->getItemsTotal() < $configuration['until']) {
            return (int) $configuration['amount'];
        }
        return 0;
    }

    public function getType(): string
    {
        return 'flat_rate_until_amount';
    }
}
