<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Cart\Model\CartItem;
use Sylius\Component\Order\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;

class OrderItem extends CartItem
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

    public function equals(OrderItemInterface $item)
    {
        /** @var $item OrderItem */
        return $this->product === $item->getProduct() || $this->product->getId() == $item->getProduct()->getId();
    }

    /**
     * Set tax
     *
     * @return integer
     */
    public function getUnitPriceTotal()
    {
        return $this->unitPrice * $this->quantity;
    }

    /**
     * Get taxTotal
     *
     * @return integer 
     */
    public function getTaxTotal()
    {
        return $this->product->getTax() * $this->quantity;
    }
}
