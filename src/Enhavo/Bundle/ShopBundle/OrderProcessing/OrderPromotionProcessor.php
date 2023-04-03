<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Promotion\Processor\PromotionProcessorInterface;

class OrderPromotionProcessor implements OrderProcessorInterface
{
    public function __construct(
        private PromotionProcessorInterface $promotionProcessor,
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        $this->promotionProcessor->process($order);
    }
}
