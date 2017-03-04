<?php
/**
 * SerializerSubscriber.php
 *
 * @since 09/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class SerializerSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    static public function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'class' => 'Enhavo\Bundle\ShopBundle\Entity\Order',
                'method' => 'onPostSerializeOrder'
            ],
            [
                'event' => 'serializer.post_serialize',
                'class' => 'Enhavo\Bundle\ShopBundle\Entity\OrderItem',
                'method' => 'onPostSerializeOrderItem'
            ]
        ];
    }

    public function onPostSerializeOrder(ObjectEvent $event)
    {
        /** @var OrderInterface $order */
        $order = $event->getObject();

        $visitor = $event->getVisitor();
        $visitor->addData('shipping_total', $order->getShippingTotal());
        $visitor->addData('shipping_tax', $order->getShippingTax());
        $visitor->addData('shipping_price', $order->getShippingPrice());
        $visitor->addData('discount_total', $order->getDiscountTotal());
        $visitor->addData('unit_price_total', $order->getUnitPriceTotal());
        $visitor->addData('unit_total', $order->getUnitTotal());
        $visitor->addData('tax_total', $order->getTaxTotal());
        $visitor->addData('free_shipping', $order->isFreeShipping());
        $visitor->addData('quantity', $order->getTotalQuantity());
    }

    public function onPostSerializeOrderItem(ObjectEvent $event)
    {
        /** @var OrderItemInterface $order */
        $orderItem = $event->getObject();

        $visitor = $event->getVisitor();
        $visitor->addData('unit_price_total', $orderItem->getUnitPriceTotal());
        $visitor->addData('tax_total', $orderItem->getTaxTotal());
        $visitor->addData('unit_tax', $orderItem->getUnitTax());
        $visitor->addData('unit_total', $orderItem->getUnitTotal());
    }
}