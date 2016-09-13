<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Cart\Model\CartItem;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Order\Model\OrderItemInterface as SyliusOrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;

class OrderItem extends CartItem implements OrderItemInterface
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function equals(SyliusOrderItemInterface $item)
    {
        /** @var $item OrderItem */
        return $this->product === $item->getProduct() || $this->product->getId() == $item->getProduct()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPriceTotal()
    {
        return $this->unitPrice * $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxTotal()
    {
        $total = 0;
        foreach($this->getUnits() as $unit) {
            $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
            foreach($adjustments as $adjustment) {
                $total += $adjustment->getAmount();
            }
        }
        return $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitTotal()
    {
        return $this->getUnitPrice() + $this->getUnitTax();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitTax()
    {
        $total = 0;
        $unit = $this->getUnits()->first();
        $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
        /** @var AdjustmentInterface $adjustment */
        foreach($adjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }
        return $total;
    }
}
