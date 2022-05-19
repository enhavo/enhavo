<?php

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\State\OrderCheckoutStates;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class CheckoutSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RouterInterface $router
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            'sylius.order.initialize_address' => 'initializeAddress',
            'sylius.order.pre_address' => 'initializeAddress',
            'sylius.order.post_address' => 'postAddress',

            'sylius.order.initialize_select_shipping' => 'initializeSelectShipping',
            'sylius.order.pre_select_shipping' => 'initializeSelectShipping',
            'sylius.order.post_select_shipping' => 'postSelectShipping',

            'sylius.order.initialize_select_payment' => 'initializeSelectPayment',
            'sylius.order.pre_select_payment' => 'initializeSelectPayment',
            'sylius.order.post_select_payment' => 'postSelectPayment',

            'sylius.order.initialize_complete' => 'initializeComplete',
            'sylius.order.pre_complete' => 'initializeComplete',
            'sylius.order.post_complete' => 'postComplete',
        ];
    }

    private function getOrder(ResourceControllerEvent $event): OrderInterface
    {
        return $event->getSubject();
    }

    public function initializeAddress(ResourceControllerEvent $event)
    {
        $order = $this->getOrder($event);
        // nothing to do here
    }

    public function postAddress(ResourceControllerEvent $event)
    {
        $event->stop('redirect');
        $event->setResponse(new RedirectResponse($this->router->generate('sylius_shop_checkout_select_shipping')));
    }

    public function initializeSelectShipping(ResourceControllerEvent $event)
    {
        $order = $this->getOrder($event);
        if (!in_array($order->getCheckoutState(), [
            OrderCheckoutStates::STATE_ADDRESSED,
            OrderCheckoutStates::STATE_SHIPPING_SKIPPED,
            OrderCheckoutStates::STATE_SHIPPING_SELECTED,
            OrderCheckoutStates::STATE_PAYMENT_SKIPPED,
            OrderCheckoutStates::STATE_PAYMENT_SELECTED,
        ])) {
            $event->stop('redirect');
            $event->setResponse(new RedirectResponse($this->router->generate('sylius_shop_checkout_address')));
        }
    }

    public function postSelectShipping(ResourceControllerEvent $event)
    {
        $event->stop('redirect');
        $event->setResponse(new RedirectResponse($this->router->generate('sylius_shop_checkout_select_payment')));
    }

    public function initializeSelectPayment(ResourceControllerEvent $event)
    {
        $order = $this->getOrder($event);
        if (!in_array($order->getCheckoutState(), [
            OrderCheckoutStates::STATE_SHIPPING_SKIPPED,
            OrderCheckoutStates::STATE_SHIPPING_SELECTED,
            OrderCheckoutStates::STATE_PAYMENT_SKIPPED,
            OrderCheckoutStates::STATE_PAYMENT_SELECTED,
        ])) {
            $event->stop('redirect');
            $event->setResponse(new RedirectResponse($this->router->generate('sylius_shop_checkout_select_shipping')));
        }
    }

    public function postSelectPayment(ResourceControllerEvent $event)
    {
        $event->stop('redirect');
        $event->setResponse(new RedirectResponse($this->router->generate('sylius_shop_checkout_confirm')));
    }

    public function initializeComplete(ResourceControllerEvent $event)
    {
        $order = $this->getOrder($event);
        if (!in_array($order->getCheckoutState(), [
            OrderCheckoutStates::STATE_PAYMENT_SELECTED,
            OrderCheckoutStates::STATE_PAYMENT_SKIPPED,
        ])) {
            $event->stop('redirect');
            $event->setResponse(new RedirectResponse($this->router->generate('sylius_shop_checkout_select_payment')));
        }
    }

    public function postComplete(ResourceControllerEvent $event)
    {
        $order = $this->getOrder($event);
        $event->stop('redirect');
        $event->setResponse(new RedirectResponse($this->router->generate('enhavo_shop_theme_payment_purchase', [
            'token' => $order->getToken()
        ])));
    }
}
