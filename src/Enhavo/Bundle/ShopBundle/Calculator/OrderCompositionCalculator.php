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
use Sylius\Component\Core\Model\AdjustmentInterface;

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

        foreach($order->getItems() as $orderItem) {
            $orderItemComposition = $this->calculateOrderItem($orderItem);

            $orderComposition->setUnitTotal($orderComposition->getUnitTotal() + $orderItemComposition->getTotal());
            $orderComposition->setNetTotal($orderComposition->getNetTotal() + $orderItemComposition->getNetTotal());
            $orderComposition->setTaxTotal($orderComposition->getTaxTotal() + $orderItemComposition->getTaxTotal());
        }


        foreach($order->getAdjustments() as $adjustment) {
            if($adjustment->getType() == AdjustmentInterface::SHIPPING_ADJUSTMENT) {
                $orderComposition->setShippingTotal($orderComposition->getShippingTotal() + $adjustment->getAmount());
            }

            if($adjustment->getType() == AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT) {
                $orderComposition->setDiscountTotal($orderComposition->getDiscountTotal() + $adjustment->getAmount());
            }
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
                if($adjustment->getType() == AdjustmentInterface::TAX_ADJUSTMENT) {
                    $orderItemComposition->setUnitTax($adjustment->getAmount());
                    $orderItemComposition->setTaxTotal($orderItemComposition->getTaxTotal() + $adjustment->getAmount());
                }
            }
        }

        return $orderItemComposition;
    }
}