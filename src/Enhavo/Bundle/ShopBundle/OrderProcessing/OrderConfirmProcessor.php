<?php
/**
 * OrderConfirmProcessor.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;


use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Sylius\Component\Cart\Provider\CartProviderInterface;

class OrderConfirmProcessor implements ProcessorInterface
{
    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(\Sylius\Component\Order\Model\OrderInterface::STATE_CONFIRMED);
        $order->setState('confirmed');
    }
}