<?php
/**
 * OrderItemCalculator.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Calculator;

use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Enhavo\Bundle\ShopBundle\Model\OrderComposition;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemComposition;

class OrderCompositionCalculator
{
    /**
     * @param OrderInterface $order
     * @return OrderComposition
     */
    public function calculateOrder(OrderInterface $order)
    {
        $orderComposition = new OrderComposition();

        $orderComposition->setTotal($order->getTotal());
        $orderComposition->setShippingTotal($order->getShippingTotal());
        $orderComposition->setDiscountTotal($order->getDiscountTotal());

        foreach($order->getItems() as $orderItem) {
            $orderItemComposition = $this->calculateOrderItem($orderItem);

            $orderComposition->setUnitTotal($orderComposition->getUnitTotal() + $orderItemComposition->getTotal());
            $orderComposition->setNetTotal($orderComposition->getNetTotal() + $orderItemComposition->getNetTotal());
            $orderComposition->setTaxTotal($orderComposition->getTaxTotal() + $orderItemComposition->getTaxTotal());
        }

        return $orderComposition;
    }

    /**
     * @param OrderItem $orderItem
     * @return OrderItemComposition
     */
    public function calculateOrderItem(OrderItem $orderItem)
    {
        $orderItemComposition = new OrderItemComposition();

        $orderItemComposition->setNetTotal($orderItem->getUnitPriceTotal());
        $orderItemComposition->setTotal($orderItem->getTotal());
        $orderItemComposition->setTaxTotal($orderItem->getTaxTotal());

        $orderItemComposition->setUnitPrice($orderItem->getUnitPrice());
        $orderItemComposition->setUnitTax($orderItem->getUnitTax());
        $orderItemComposition->setUnitTotal($orderItem->getUnitTotal());

        return $orderItemComposition;
    }
}