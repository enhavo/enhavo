<?php

namespace Enhavo\Bundle\ShopBundle\Payment\Provider;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;

interface OrderPaymentProviderInterface
{
    public function provideOrderPayment(OrderInterface $order, string $targetState): ?PaymentInterface;
}
