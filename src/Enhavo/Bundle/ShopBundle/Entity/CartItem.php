<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 9:56 AM
 */
namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Cart\Model\CartItem as BaseCartItem;
use Sylius\Component\Order\Model\OrderItemInterface;

class CartItem extends BaseCartItem
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
        /** @var $item CartItem */
        return $this->product === $item->getProduct() || $this->product->getId() == $item->getProduct()->getId();
    }

    public function increaseQuantity()
    {
        $this->quantity++;
    }
}