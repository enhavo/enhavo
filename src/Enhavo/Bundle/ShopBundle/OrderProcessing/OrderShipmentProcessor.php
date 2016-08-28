<?php
/**
 * OrderShipmentProcessor.php
 *
 * @since 24/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Shipping\Resolver\DefaultShippingMethodResolverInterface;
use Sylius\Component\Shipping\Calculator\DelegatingCalculatorInterface;
use Doctrine\Common\Proxy\Proxy;

class OrderShipmentProcessor
{
    /**
     * @var DefaultShippingMethodResolverInterface
     */
    protected $defaultShippingMethodResolver;

    /**
     * @var FactoryInterface
     */
    protected $shipmentFactory;

    /**
     * @var DelegatingCalculatorInterface
     */
    protected $shippingCalculator;

    /**
     * @var FactoryInterface
     */
    protected $adjustmentFactory;

    /**
     * OrderShipmentProcessor constructor.
     * @param DefaultShippingMethodResolverInterface $defaultShippingMethodResolver
     * @param FactoryInterface $shipmentFactory
     * @param DelegatingCalculatorInterface $shippingCalculator
     * @param FactoryInterface $adjustmentFactory
     */
    public function __construct(
        DefaultShippingMethodResolverInterface $defaultShippingMethodResolver,
        FactoryInterface $shipmentFactory,
        DelegatingCalculatorInterface $shippingCalculator,
        FactoryInterface $adjustmentFactory
    ) {
        $this->defaultShippingMethodResolver = $defaultShippingMethodResolver;
        $this->shipmentFactory = $shipmentFactory;
        $this->shippingCalculator = $shippingCalculator;
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function processOrderShipment(OrderInterface $order)
    {
        $shipment = $this->getOrderShipment($order);
        $this->calculate($shipment, $order);
    }

    /**
     * @param OrderInterface $order
     *
     * @return ShipmentInterface
     */
    private function getOrderShipment(OrderInterface $order)
    {
        if ($order->getShipment()) {
            return $order->getShipment();
        }

        /** @var ShipmentInterface $shipment */
        $shipment = $this->shipmentFactory->createNew();
        $order->setShipment($shipment);
        $shipment->setMethod($this->defaultShippingMethodResolver->getDefaultShippingMethod($shipment));

        return $shipment;
    }

    private function calculate(ShipmentInterface $shipment, OrderInterface $order)
    {
        $amount = $this->shippingCalculator->calculate($shipment);
        $adjustment = $this->getShippingAdjustment($order);
        $adjustment->setAmount($amount);
    }

    private function getShippingAdjustment(OrderInterface $order)
    {
        /** @var AdjustmentInterface $adjustment */
        foreach($order->getAdjustments() as $adjustment) {
            if($adjustment->getType() == 'shipping') {
                return $adjustment;
            }
        }

        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType('shipping');
        $order->addAdjustment($adjustment);
        return $adjustment;
    }
}