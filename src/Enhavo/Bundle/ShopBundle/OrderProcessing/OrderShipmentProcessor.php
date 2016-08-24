<?php
/**
 * OrderShipmentProcessor.php
 *
 * @since 24/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Shipping\Resolver\DefaultShippingMethodResolverInterface;

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
     * @param DefaultShippingMethodResolverInterface $defaultShippingMethodResolver
     * @param FactoryInterface $shipmentFactory
     */
    public function __construct(
        DefaultShippingMethodResolverInterface $defaultShippingMethodResolver,
        FactoryInterface $shipmentFactory
    ) {
        $this->defaultShippingMethodResolver = $defaultShippingMethodResolver;
        $this->shipmentFactory = $shipmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function processOrderShipment(OrderInterface $order)
    {
        $this->getOrderShipment($order);
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
}