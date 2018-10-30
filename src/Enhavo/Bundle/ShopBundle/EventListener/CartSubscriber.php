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
use Enhavo\Bundle\ShopBundle\OrderProcessing\OrderTaxProcessor;
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
     * @var ProcessorInterface
     */
    private $taxProcessor;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        OrderShipmentProcessor $orderShipmentProcessor,
        PromotionProcessorInterface $promotionProcessor,
        ProcessorInterface $orderInitProcessor,
        OrderTaxProcessor $taxProcessor,
        EntityManagerInterface $em
    )
    {
        $this->orderShipmentProcessor = $orderShipmentProcessor;
        $this->promotionProcessor = $promotionProcessor;
        $this->orderInitProcessor = $orderInitProcessor;
        $this->taxProcessor = $taxProcessor;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SyliusCartEvents::CART_INITIALIZE => ['init', 10],
            SyliusCartEvents::CART_CHANGE => ['change', 10],
            SyliusCartEvents::CART_ABANDON => ['abandon', 10],
            SyliusCartEvents::ITEM_ADD_INITIALIZE => ['add', 10],
        ];
    }

    public function change(GenericEvent $event)
    {
        $cart = $event->getSubject();
        $this->promotionProcessor->process($cart);
        $this->orderShipmentProcessor->process($cart);
        $this->promotionProcessor->process($cart);
        $this->orderShipmentProcessor->process($cart);
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

    public function add(GenericEvent $event)
    {
        // nothing to do yet
    }
}