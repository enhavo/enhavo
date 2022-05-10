<?php
/**
 * OrderPaymentProcessor.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Payment\Provider\OrderPaymentProviderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Enhavo\Bundle\ShopBundle\State\OrderPaymentStates;
use Enhavo\Bundle\ShopBundle\Exception\NotProvidedOrderPaymentException;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Webmozart\Assert\Assert;

class OrderPaymentProcessor implements OrderProcessorInterface
{
    public function __construct(
        private OrderPaymentProviderInterface $orderPaymentProvider,
    ) {}

    public function process(BaseOrderInterface $order): void
    {
        /** @var  $order */
        Assert::isInstanceOf($order, OrderInterface::class);

        if (OrderInterface::STATE_CANCELLED === $order->getState()) {
            return;
        }

        if (0 === $order->getTotal()) {
            $removablePayments = $order->getPayments()->filter(function (PaymentInterface $payment): bool {
                return $payment->getState() === OrderPaymentStates::STATE_CART;
            });

            foreach ($removablePayments as $payment) {
                $order->removePayment($payment);
            }

            return;
        }

        $lastPayment = $order->getLastPayment(PaymentInterface::STATE_CART);
        if (null !== $lastPayment) {
            $lastPayment->setCurrencyCode($order->getCurrencyCode());
            $lastPayment->setAmount($order->getTotal());

            return;
        }

        try {
            $newPayment = $this->orderPaymentProvider->provideOrderPayment($order, PaymentInterface::STATE_CART);
            $order->addPayment($newPayment);
        } catch (NotProvidedOrderPaymentException $exception) {
            return;
        }
    }
}
