<?php
/**
 * CartSubscriber.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\OrderProcessing\OrderShipmentProcessor;
use Sylius\Component\Cart\SyliusCartEvents;
use Sylius\Component\Promotion\Processor\PromotionProcessorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CartSubscriber implements EventSubscriberInterface
{
    /**
     * @var OrderShipmentProcessor
     */
    private $orderShipmentProcessor;

    /**
     * @var PromotionProcessorInterface
     */
    private $promotionProcessor;

    public function __construct(OrderShipmentProcessor $orderShipmentProcessor, PromotionProcessorInterface $promotionProcessor)
    {
        $this->orderShipmentProcessor = $orderShipmentProcessor;
        $this->promotionProcessor = $promotionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SyliusCartEvents::CART_CHANGE => 'change',
        ];
    }

    public function change(GenericEvent $event)
    {
        $cart = $event->getSubject();
        $this->orderShipmentProcessor->processOrderShipment($cart);
        $this->promotionProcessor->process($cart);
    }
}