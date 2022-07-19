<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Shipping\DelegatingCalculatorInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Shipping\Calculator\UndefinedShippingMethodException;
use Webmozart\Assert\Assert;

class ShippingChargesProcessor implements OrderProcessorInterface
{
    public function __construct(
        private FactoryInterface $adjustmentFactory,
        private DelegatingCalculatorInterface $shippingCalculator
    )
    {}

    public function process(BaseOrderInterface $order): void
    {
        /** @var OrderInterface $order */
        Assert::isInstanceOf($order, OrderInterface::class);

        $order->removeAdjustments(AdjustmentInterface::SHIPPING_ADJUSTMENT);
        $order->removeAdjustments(AdjustmentInterface::TAX_SHIPPING_ADJUSTMENT);

        foreach ($order->getShipments() as $shipment) {
            try {
                $shippingCharge = $this->shippingCalculator->calculate($shipment);
                $shippingTaxCharge = $this->shippingCalculator->calculateTax($shipment);
                $shippingMethod = $shipment->getMethod();

                $order->addAdjustment($this->createShippingAdjustment($shippingCharge, $shippingMethod));
                $order->addAdjustment($this->createShippingTaxAdjustment($shippingTaxCharge, $shippingMethod));
            } catch (UndefinedShippingMethodException $exception) {
            }
        }
    }

    private function createShippingAdjustment($amount, $shippingMethod)
    {
        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType(AdjustmentInterface::SHIPPING_ADJUSTMENT);
        $adjustment->setAmount($amount);
        $adjustment->setLabel($shippingMethod->getName());
        $adjustment->setDetails([
            'shippingMethodCode' => $shippingMethod->getCode(),
            'shippingMethodName' => $shippingMethod->getName(),
            'shippingMethodId' => $shippingMethod->getId(),
        ]);
        return $adjustment;
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
