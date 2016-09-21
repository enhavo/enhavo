<?php
/**
 * OrderItemTest.php
 *
 * @since 12/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;

class OrderItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $tax
     * @return OrderItemUnitInterface
     */
    protected function getUnit($tax, $orderItem) {
        $adjustment = $this->getMockBuilder(AdjustmentInterface::class)->getMock();
        $adjustment->method('getAmount')->willReturn(19);

        $unit = $this->getMockBuilder(OrderItemUnitInterface::class)->getMock();
        $unit->method('getAdjustmentsTotal')->willReturn($tax);
        $unit->method('getAdjustments')->willReturn(new ArrayCollection([$adjustment]));
        $unit->method('getOrderItem')->willReturn($orderItem);

        return $unit;
    }

    public function testGetUnitTax() {
        $orderItem = new OrderItem();
        $orderItem->setUnitPrice(100);
        $orderItem->addUnit($this->getUnit(19, $orderItem));
        $orderItem->addUnit($this->getUnit(19, $orderItem));

        static::assertEquals(19, $orderItem->getUnitTax());
    }

    public function testGetTaxTotal() {
        $orderItem = new OrderItem();
        $orderItem->setUnitPrice(100);
        $orderItem->addUnit($this->getUnit(19, $orderItem));
        $orderItem->addUnit($this->getUnit(19, $orderItem));

        static::assertEquals(38, $orderItem->getTaxTotal());
    }

    public function testGetUnitPriceTotal() {
        $orderItem = new OrderItem();
        $orderItem->setUnitPrice(100);
        $orderItem->addUnit($this->getUnit(19, $orderItem));
        $orderItem->addUnit($this->getUnit(19, $orderItem));

        static::assertEquals(200, $orderItem->getUnitPriceTotal());
    }

    public function testGetUnitTotal() {
        $orderItem = new OrderItem();
        $orderItem->setUnitPrice(100);
        $orderItem->addUnit($this->getUnit(19, $orderItem));
        $orderItem->addUnit($this->getUnit(19, $orderItem));

        static::assertEquals(119, $orderItem->getUnitTotal());
    }
}