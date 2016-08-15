<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Cart\Model\CartItem;
use Sylius\Component\Order\Model\OrderItemInterface;

class OrderItem extends CartItem
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
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

    public function increaseQuantity()
    {
        $this->quantity++;
    }
}