<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 10:02 AM
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ContentBundle\Entity\Content;

class Product extends Content
{
    /**
     * @var integer
     */
    private $price;


    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
}
