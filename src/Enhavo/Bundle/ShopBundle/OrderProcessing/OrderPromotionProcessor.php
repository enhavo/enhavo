<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Promotion\Processor\PromotionProcessorInterface;

class OrderPromotionProcessor implements OrderProcessorInterface
{
    public function __construct(
        private PromotionProcessorInterface $promotionProcessor,
    )
    {
    }

    public function process(BaseOrderInterface $order): void
    {
        $this->promotionProcessor->process($order);
    }
}
