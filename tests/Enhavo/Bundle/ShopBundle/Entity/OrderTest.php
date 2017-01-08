<?php
/**
 * OrderTest.php
 *
 * @since 12/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\ShipmentInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param int $type
     * @param int $amount
     * @return AdjustmentInterface
     */
    protected function createAdjustment($type, $amount, $originType = null)
    {
        $adjustment = $this->getMockBuilder(AdjustmentInterface::class)->getMock();
        $adjustment->method('getType')->willReturn($type);
        $adjustment->method('getAmount')->willReturn($amount);
        $adjustment->method('getAmount')->willReturn($amount);
        $adjustment->method('getOriginType')->willReturn($originType);

        return $adjustment;
    }

    /**
     * @param int $unitPriceTotal
     * @param int $taxTotal
     * @param int $quantity
     * @return OrderItemInterface
     */
    protected function createItem($unitPriceTotal, $taxTotal, $quantity = 1)
    {
        $item = $this->getMockBuilder(OrderItemInterface::class)->getMock();
        $item->method('getTotal')->willReturn($taxTotal + $unitPriceTotal);
        $item->method('getUnitPriceTotal')->willReturn($unitPriceTotal);
        $item->method('getTaxTotal')->willReturn($taxTotal);
        $item->method('getQuantity')->willReturn($quantity);

        return $item;
    }

    public function testGetShippingTotal()
    {
        $order = new Order();

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1000));
        static::assertEquals(1000, $order->getShippingTotal());

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1500));
        static::assertEquals(2500, $order->getShippingTotal());

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT, -1000));
        static::assertEquals(1500, $order->getShippingTotal());
    }

    public function testGetDiscountTotal()
    {
        $order = new Order();

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1000));
        static::assertEquals(-500, $order->getDiscountTotal());

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, -1500));
        static::assertEquals(-2000, $order->getDiscountTotal());
    }

    public function testGetUnitTotal()
    {
        $order = new Order();

        $order->addItem($this->createItem(1000, 190));
        $order->addItem($this->createItem(2000, 380));

        static::assertEquals(3570, $order->getUnitTotal());
    }

    public function testGetUnitPriceTotal()
    {
        $order = new Order();

        $order->addItem($this->createItem(1000, 190));
        $order->addItem($this->createItem(2000, 380));

        static::assertEquals(3000, $order->getUnitPriceTotal());
    }

    public function testGetTaxTotal()
    {
        $order = new Order();

        $order->addItem($this->createItem(1000, 190));
        $order->addItem($this->createItem(2000, 380));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::TAX_ADJUSTMENT, 200));

        static::assertEquals(770, $order->getTaxTotal());
    }

    public function testSummaryOfTotals()
    {
        $order = new Order();

        $order->addItem($this->createItem(1000, 190));
        $order->addItem($this->createItem(2000, 380));

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1200));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::TAX_ADJUSTMENT, 200));

        $summary = $order->getShippingTotal() + $order->getDiscountTotal() + $order->getTaxTotal() + $order->getUnitPriceTotal();
        static::assertEquals(3970, $summary);
    }

    public function testShippingTax()
    {
        $order = new Order();

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1200));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::TAX_ADJUSTMENT, 400, ShipmentInterface::class));

        static::assertEquals(400, $order->getShippingTax());
    }

    public function testShippingPrice()
    {
        $order = new Order();

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1200));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::TAX_ADJUSTMENT, 400, ShipmentInterface::class));

        static::assertEquals(700, $order->getShippingPrice());
    }

    public function testShippingTotal()
    {
        $order = new Order();

        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT, -500));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::SHIPPING_ADJUSTMENT, 1200));
        $order->addAdjustment($this->createAdjustment(AdjustmentInterface::TAX_ADJUSTMENT, 400, ShipmentInterface::class));

        static::assertEquals(1100, $order->getShippingTotal());
    }
}