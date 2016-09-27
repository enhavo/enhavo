<?php
/**
 * CartSubscriber.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
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

    /**
     * @var ProcessorInterface
     */
    private $orderInitProcessor;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        OrderShipmentProcessor $orderShipmentProcessor,
        PromotionProcessorInterface $promotionProcessor,
        ProcessorInterface $orderInitProcessor,
        EntityManagerInterface $em
    )
    {
        $this->orderShipmentProcessor = $orderShipmentProcessor;
        $this->promotionProcessor = $promotionProcessor;
        $this->orderInitProcessor = $orderInitProcessor;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SyliusCartEvents::CART_INITIALIZE => 'init',
            SyliusCartEvents::CART_CHANGE => 'change',
            SyliusCartEvents::CART_ABANDON => 'abandon',
        ];
    }

    public function change(GenericEvent $event)
    {
        $cart = $event->getSubject();
        $this->orderShipmentProcessor->process($cart);
        $this->promotionProcessor->process($cart);
    }

    public function init(GenericEvent $event)
    {
        $cart = $event->getSubject();
        $this->orderInitProcessor->process($cart);
    }

    public function abandon(GenericEvent $event)
    {
        $cart = $event->getSubject();
        if($cart instanceof OrderInterface) {
            $cart->setExpiresAt(new \DateTime());
        }
        $this->em->flush();
    }
}