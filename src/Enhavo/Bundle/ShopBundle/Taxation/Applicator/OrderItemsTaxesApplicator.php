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
            $taxRate = $this->taxRateResolver->resolve($item->getProduct());
            if (null === $taxRate) {
                continue;
            }

            $taxAmount = $this->calculator->calculate($item->getProduct()->getPrice(), $taxRate);

            foreach ($item->getUnits() as $unit) {
                $this->addAdjustment($unit, $taxAmount, $taxRate);
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
}
