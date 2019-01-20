<?php
/**
 * OrderAddressingProcessor.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Order\OrderCheckoutStates;

class OrderAddressingProcessor implements ProcessorInterface
{
    /**
     * @var ProcessorInterface
     */
    private $orderShipmentProcessor;

    public function __construct(ProcessorInterface $orderShipmentProcessor)
    {
        $this->orderShipmentProcessor = $orderShipmentProcessor;
    }

    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(OrderCheckoutStates::STATE_ADDRESSED);
        $this->orderShipmentProcessor->process($order);
    }
}
