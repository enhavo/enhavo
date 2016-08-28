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

        foreach($order->getItems() as $orderItem) {
            $orderItemComposition = $this->calculateOrderItem($orderItem);

            $orderComposition->setTotal($orderComposition->getTotal() + $orderItemComposition->getTotal());
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
        $orderItemComposition->setNetTotal($orderItem->getUnitPrice() * $orderItem->getQuantity());
        $orderItemComposition->setTotal($orderItem->getTotal());
        $orderItemComposition->setUnitPrice($orderItem->getUnitPrice());
        $orderItemComposition->setUnitTotal($orderItem->getProduct()->getTax() + $orderItem->getUnitPrice());
        $orderItemComposition->setUnitTax($orderItem->getProduct()->getTax());

        foreach($orderItem->getUnits() as $unit) {
            foreach($unit->getAdjustments() as $adjustment) {
                if($adjustment->getType() == 'tax') {
                    $orderItemComposition->setUnitTax($adjustment->getAmount());
                    $orderItemComposition->setTaxTotal($orderItemComposition->getTaxTotal() + $adjustment->getAmount());
                }
            }
        }

        return $orderItemComposition;
    }
}