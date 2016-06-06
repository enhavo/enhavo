<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 9:56 AM
 */
namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Cart\Model\CartItem as BaseCartItem;

class CartItem extends BaseCartItem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Product
     */
    private $product;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

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
}