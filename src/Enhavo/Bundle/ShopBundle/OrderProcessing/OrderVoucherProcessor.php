<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Manager\VoucherManager;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

class OrderVoucherProcessor implements OrderProcessorInterface
{
    public function __construct(
        private AdjustmentFactoryInterface $adjustmentFactory,
        private VoucherManager $voucherManager,
    )
    {}

    public function process(OrderInterface $order): void
    {
        $order->removeAdjustmentsRecursively(AdjustmentInterface::VOUCHER_ADJUSTMENT);

        /** @var $order \Enhavo\Bundle\ShopBundle\Model\OrderInterface */
        foreach ($order->getVouchers() as $voucher) {
            if ($order->getTotal() > 0) {
                $amount = $voucher->getAvailableAmount();
                if ($amount > $order->getTotal()) {
                    if ($voucher->isPartialRedeemable()) {
                        $adjustment = $this->createAdjustment($order->getTotal(), $voucher);
                        $order->addAdjustment($adjustment);
                    }
                } elseif ($amount < $order->getTotal()) {
                    $adjustment = $this->createAdjustment($amount, $voucher);
                    $order->addAdjustment($adjustment);
                }
            }
        }
    }

    private function createAdjustment($amount, $voucher)
    {
        return $this->adjustmentFactory->createWithData(AdjustmentInterface::VOUCHER_ADJUSTMENT, 'vocher', -1*$amount, false, [
            'voucher' => $voucher->getId()
        ]);
    }
}
