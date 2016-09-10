<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Cart\Model\CartItemInterface;

interface OrderItemInterface extends CartItemInterface
{
    /**
     * @return void
     */
    public function setProduct();

    /**
     * @return ProductInterface
     */
    public function getProduct();
}