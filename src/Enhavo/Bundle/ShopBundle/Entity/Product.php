<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 10:02 AM
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

class Product extends Content implements ShippableInterface, ProductInterface
{
    /**
     * @var integer
     */
    private $price;

    /**
     * @var ShippingCategoryInterface
     */
    private $shippingCategory;

    /**
     * @var TaxRateInterface
     */
    private $taxRate;

    /**
     * @var integer
     */
    private $height;

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $depth;

    /**
     * @var integer
     */
    private $volume;

    /**
     * @var integer
     */
    private $weight;

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

    /**
     * @return ShippingCategoryInterface
     */
    public function getShippingCategory()
    {
        return $this->shippingCategory;
    }

    /**
     * @param ShippingCategoryInterface $shippingCategory
     * @return $this
     */
    public function setShippingCategory(ShippingCategoryInterface $shippingCategory) // This method is not required.
    {
        $this->shippingCategory = $shippingCategory;

        return $this;
    }

    /**
     * @return int
     */
    public function getShippingWeight()
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getShippingWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getShippingHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getShippingDepth()
    {
        return $this->depth;
    }

    /**
     * @return int
     */
    public function getShippingVolume()
    {
        return $this->volume;
    }

    /**
     * Set taxRate
     *
     * @param TaxRateInterface $taxRate
     * @return Product
     */
    public function setTaxRate(TaxRateInterface $taxRate = null)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return TaxRateInterface
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Product
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Product
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set depth
     *
     * @param integer $depth
     * @return Product
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth
     *
     * @return integer 
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set volume
     *
     * @param integer $volume
     * @return Product
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return integer 
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    public function getTax()
    {
        return intval($this->getPrice() * $this->getTaxRate()->getAmount());
    }

    public function getUnitPriceTotal()
    {
        return $this->getTax() + $this->getPrice();
    }
}
