<?php
/**
 * OrderShipmentProcessor.php
 *
 * @since 24/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Enhavo\Bundle\ShopBundle\Shipping\ShippingTaxCalculatorInterface;
use Sylius\Component\Shipping\Exception\UnresolvedDefaultShippingMethodException;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Shipping\Resolver\DefaultShippingMethodResolverInterface;
use Sylius\Component\Shipping\Calculator\DelegatingCalculatorInterface;

class OrderShipmentProcessor implements ProcessorInterface
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
     * @var ShippingTaxCalculatorInterface
     */
    protected $shippingTaxCalculator;

    /**
     * OrderShipmentProcessor constructor.
     * @param DefaultShippingMethodResolverInterface $defaultShippingMethodResolver
     * @param FactoryInterface $shipmentFactory
     * @param DelegatingCalculatorInterface $shippingCalculator
     * @param ShippingTaxCalculatorInterface $shippingTaxCalculator
     * @param FactoryInterface $adjustmentFactory
     */
    public function __construct(
        DefaultShippingMethodResolverInterface $defaultShippingMethodResolver,
        FactoryInterface $shipmentFactory,
        DelegatingCalculatorInterface $shippingCalculator,
        ShippingTaxCalculatorInterface $shippingTaxCalculator,
        FactoryInterface $adjustmentFactory
    ) {
        $this->defaultShippingMethodResolver = $defaultShippingMethodResolver;
        $this->shipmentFactory = $shipmentFactory;
        $this->shippingCalculator = $shippingCalculator;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->shippingTaxCalculator = $shippingTaxCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order)
    {
        $shipment = $this->getOrderShipment($order);
        $this->calculate($shipment, $order);
    }

    /**
     * @param OrderInterface $order
     *
     * @throws UnresolvedDefaultShippingMethodException
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

        $amount = $this->shippingTaxCalculator->calculate($order);
        $adjustment = $this->getShippingTaxAdjustment($order);
        $adjustment->setAmount($amount);
    }

    private function getShippingAdjustment(OrderInterface $order)
    {
        /** @var AdjustmentInterface $adjustment */
        foreach($order->getAdjustments() as $adjustment) {
            if($adjustment->getType() === \Sylius\Component\Core\Model\AdjustmentInterface::SHIPPING_ADJUSTMENT) {
                return $adjustment;
            }
        }

        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType(\Sylius\Component\Core\Model\AdjustmentInterface::SHIPPING_ADJUSTMENT);
        $order->addAdjustment($adjustment);
        return $adjustment;
    }

    private function getShippingTaxAdjustment(OrderInterface $order)
    {
        /** @var AdjustmentInterface $adjustment */
        foreach($order->getAdjustments() as $adjustment) {
            if($adjustment->getType() === \Sylius\Component\Core\Model\AdjustmentInterface::TAX_ADJUSTMENT) {
                if($adjustment->getOriginType() === ShipmentInterface::class) {
                    return $adjustment;
                }
            }
        }

        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType(\Sylius\Component\Core\Model\AdjustmentInterface::TAX_ADJUSTMENT);
        $adjustment->setOriginType(ShipmentInterface::class);
        $order->addAdjustment($adjustment);
        return $adjustment;
    }
}