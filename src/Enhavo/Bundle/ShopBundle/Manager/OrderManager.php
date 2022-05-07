<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\ShopBundle\Address\AddressProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\State\OrderPaymentStates;
use Enhavo\Bundle\ShopBundle\State\OrderShippingStates;

class OrderManager
{
    public function __construct(
        private AddressProviderInterface $addressProvider,
        private TokenGeneratorInterface $tokenGenerator,
        private MailerManager $mailerManager,
    )
    {}

    public function assignAddress(OrderInterface $order)
    {
        if ($order->getBillingAddress() === null) {
            $order->setBillingAddress($this->addressProvider->getBillingAddress());
        }

        if ($order->getShippingAddress() === null) {
            $order->setShippingAddress($this->addressProvider->getShippingAddress());
        }
    }

    public function assignCheckoutShipmentState(OrderInterface $order)
    {
        $order->setShippingState(OrderShippingStates::STATE_READY);
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
}
