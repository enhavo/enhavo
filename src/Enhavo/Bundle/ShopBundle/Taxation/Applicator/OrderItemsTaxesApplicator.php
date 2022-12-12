<?php

namespace Enhavo\Bundle\ShopBundle\Taxation\Applicator;

use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;
use Webmozart\Assert\Assert;

class OrderItemsTaxesApplicator implements OrderTaxesApplicatorInterface
{
    public function __construct(
        private CalculatorInterface $calculator,
        private AdjustmentFactoryInterface $adjustmentFactory,
        private TaxRateResolverInterface $taxRateResolver
    ) {}

    public function apply(OrderInterface $order): void
    {
        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {

            if ($item->getQuantity() === 0) {
                continue;
            }

            $taxRate = $this->taxRateResolver->resolve($item->getProduct());
            if (null === $taxRate) {
                continue;
            }

            $totalTaxAmount = $this->calculator->calculate($item->getTotal(), $taxRate);
            $splitTaxes = $this->distribute($totalTaxAmount, $item->getQuantity());

            $i = 0;
            foreach ($item->getUnits() as $unit) {
                if (0 === $splitTaxes[$i]) {
                    continue;
                }

                $this->addAdjustment($unit, $splitTaxes[$i], $taxRate);
                ++$i;
            }
        }
    }

    private function addAdjustment(OrderItemUnitInterface $unit, int $taxAmount, TaxRateInterface $taxRate): void
    {
        $unitTaxAdjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::TAX_ADJUSTMENT,
            $taxRate->getLabel(),
            $taxAmount,
            $taxRate->isIncludedInPrice(),
            [
                'taxRateCode' => $taxRate->getCode(),
                'taxRateName' => $taxRate->getName(),
                'taxRateAmount' => $taxRate->getAmount(),
            ]
        );
        $unit->addAdjustment($unitTaxAdjustment);
    }

    public function distribute(float $amount, int $numberOfTargets): array
    {
        $sign = $amount < 0 ? -1 : 1;
        $amount = abs($amount);

        $low = (int) ($amount / $numberOfTargets);
        $high = $low + 1;

        $remainder = $amount % $numberOfTargets;
        $result = [];

        for ($i = 0; $i < $remainder; ++$i) {
            $result[] = $high * $sign;
        }

        for ($i = $remainder; $i < $numberOfTargets; ++$i) {
            $result[] = $low * $sign;
        }

        return $result;
    }
}
