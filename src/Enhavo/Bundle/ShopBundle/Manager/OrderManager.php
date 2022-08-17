<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\ShopBundle\Address\AddressProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\State\OrderCheckoutStates;
use Enhavo\Bundle\ShopBundle\State\OrderPaymentStates;
use Enhavo\Bundle\ShopBundle\State\OrderShippingStates;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

class OrderManager
{
    public function __construct(
        private AddressProviderInterface $addressProvider,
        private TokenGeneratorInterface $tokenGenerator,
        private MailerManager $mailerManager,
        private OrderProcessorInterface $orderProcessor,
        private EntityManagerInterface $entityManager,
    )
    {}

    public function assignAddress(OrderInterface $order)
    {
        if ($order->getBillingAddress() === null) {
            $order->setBillingAddress($this->addressProvider->getAddress()->getBillingAddress());
        }

        if ($order->getShippingAddress() === null) {
            $order->setShippingAddress($this->addressProvider->getAddress()->getShippingAddress());
        }
    }

    public function assignCheckoutShipmentState(OrderInterface $order)
    {
        $order->setShippingState(OrderShippingStates::STATE_READY);
    }

    public function assignCheckoutCompletedAt(OrderInterface $order)
    {
        $order->setCheckoutCompletedAt(new \DateTime());
    }

    public function assignCheckoutPaymentState(OrderInterface $order)
    {
        $order->setPaymentState(OrderPaymentStates::STATE_AWAITING_PAYMENT);
    }

    public function assignTokenValue(OrderInterface $order): void
    {
        if (null === $order->getToken()) {
            $order->setToken($this->tokenGenerator->generateToken());
        }
    }

    public function sendConfirmMail(OrderInterface $order)
    {
        $this->mailerManager->sendMail('shop_confirm', $order);
    }

    public function sendNotificationMail(OrderInterface $order)
    {
        $this->mailerManager->sendMail('shop_notification', $order);
    }

    public function clearCart(OrderInterface $order)
    {
        if ($order->getCheckoutState() === OrderCheckoutStates::STATE_COMPLETED) {
            throw new \InvalidArgumentException('Order is already completed');
        }

        $order->clearItems();
        $this->orderProcessor->process($order);
        $this->em->flush();
    }
}
