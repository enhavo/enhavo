<?php

namespace Enhavo\Bundle\ShopBundle\Taxation\Applicator;

use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ShipmentInterface;
use Enhavo\Bundle\ShopBundle\Shipping\DelegatingCalculatorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Shipping\Calculator\UndefinedShippingMethodException;

class OrderShipmentTaxesApplicator implements OrderTaxesApplicatorInterface
{
    public function __construct(
        private FactoryInterface $adjustmentFactory,
        private DelegatingCalculatorInterface $shippingCalculator
    )
    {}

    public function apply(OrderInterface $order): void
    {
        $order->removeAdjustments(AdjustmentInterface::TAX_SHIPPING_ADJUSTMENT);

        /** @var ShipmentInterface $shipment */
        foreach ($order->getShipments() as $shipment) {
            try {
                $shippingTaxCharge = $this->shippingCalculator->calculateTax($order->getShippingTotal(), $shipment);
                $order->addAdjustment($this->createShippingTaxAdjustment($shippingTaxCharge, $shipment->getMethod()));
            } catch (UndefinedShippingMethodException $exception) {
            }
        }
    }

    private function createShippingTaxAdjustment($amount, $shippingMethod)
    {
        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType(AdjustmentInterface::TAX_SHIPPING_ADJUSTMENT);
        $adjustment->setAmount($amount);
        $adjustment->setLabel($shippingMethod->getName());
        $adjustment->setDetails([
            'shippingMethodCode' => $shippingMethod->getCode(),
            'shippingMethodName' => $shippingMethod->getName(),
            'shippingMethodId' => $shippingMethod->getId(),
        ]);
        return $adjustment;
    }
}
