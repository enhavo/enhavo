<?php

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\CartActions;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class OrderRecalculationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private OrderProcessorInterface $orderProcessor
    )
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            CartActions::ADD => 'recalculateOrder',
            CartActions::REMOVE => 'recalculateOrder',
            SyliusCartEvents::CART_CHANGE => 'recalculateOrder',
        ];
    }

    public function recalculateOrder(GenericEvent $event): void
    {
        $order = $event->getSubject();
        $this->orderProcessor->process($order);
    }
}
