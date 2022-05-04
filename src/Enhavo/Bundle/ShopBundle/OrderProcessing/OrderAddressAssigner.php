<?php
/**
 * OrderAddressingProcessor.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Manager\OrderManager;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

class OrderAddressAssigner implements OrderProcessorInterface
{
    public function __construct(
        private OrderManager $orderManager
    ) {}

    public function process(OrderInterface $order): void
    {
        $this->orderManager->assignAddress($order);
    }
}
