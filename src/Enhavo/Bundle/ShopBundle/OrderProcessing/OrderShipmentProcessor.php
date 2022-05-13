<?php
/**
 * OrderShipmentProcessor.php
 *
 * @since 24/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Shipping\Exception\UnresolvedDefaultShippingMethodException;
use Enhavo\Bundle\ShopBundle\Model\ShipmentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Shipping\Resolver\DefaultShippingMethodResolverInterface;
use Sylius\Component\Shipping\Resolver\ShippingMethodsResolverInterface;
use Webmozart\Assert\Assert;

class OrderShipmentProcessor implements OrderProcessorInterface
{
    public function __construct(
        private DefaultShippingMethodResolverInterface $defaultShippingMethodResolver,
        private FactoryInterface $shipmentFactory,
        private ShippingMethodsResolverInterface $shippingMethodsResolver,
    ) {}

    public function process(BaseOrderInterface $order): void
    {
        /** @var OrderInterface $order */
        Assert::isInstanceOf($order, OrderInterface::class);

        if ($order->isEmpty() || !$order->isShippable()) {
            $order->removeShipments();
            return;
        }

        if ($order->hasShipments()) {
            $this->recalculateExistingShipmentWithProperMethod($order);
            return;
        }

        $this->createNewOrderShipment($order);
    }

    private function createNewOrderShipment(OrderInterface $order): void
    {
        /** @var ShipmentInterface $shipment */
        $shipment = $this->shipmentFactory->createNew();
        $shipment->setOrder($order);

        try {
            $this->processShipmentUnits($order, $shipment);

            $shipment->setMethod($this->defaultShippingMethodResolver->getDefaultShippingMethod($shipment));

            $order->addShipment($shipment);
        } catch (UnresolvedDefaultShippingMethodException $exception) {
            foreach ($shipment->getUnits() as $unit) {
                $shipment->removeUnit($unit);
            }
        }
    }

    private function processShipmentUnits(BaseOrderInterface $order, ShipmentInterface $shipment): void
    {
        foreach ($shipment->getUnits() as $unit) {
            $shipment->removeUnit($unit);
        }

        /** @var OrderInterface $order */
        Assert::isInstanceOf($order, OrderInterface::class);

        foreach ($order->getItemUnits() as $itemUnit) {
            if (null === $itemUnit->getShipment()) {
                $shipment->addUnit($itemUnit);
            }
        }
    }

    private function recalculateExistingShipmentWithProperMethod(OrderInterface $order): void
    {
        /** @var ShipmentInterface $shipment */
        $shipment = $order->getShipments()->first();

        $this->processShipmentUnits($order, $shipment);

        if (null === $this->shippingMethodsResolver) {
            return;
        }

        if (!in_array($shipment->getMethod(), $this->shippingMethodsResolver->getSupportedMethods($shipment), true)) {
            try {
                $shipment->setMethod($this->defaultShippingMethodResolver->getDefaultShippingMethod($shipment));
            } catch (UnresolvedDefaultShippingMethodException $exception) {
                return;
            }
        }
    }
}
