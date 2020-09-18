<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.20
 * Time: 16:32
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Sylius\Component\Product\Model\ProductVariant as SyliusProductVariant;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

class ProductVariant extends SyliusProductVariant
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var FileInterface
     */
    private $picture;

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
     * @var boolean
     */
    private $shippingRequired;

    /**
     * @var integer
     */
    private $stock;

    /**
     * @var boolean
     */
    private $stockTracked;

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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): ProductVariant
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(?int $height): ProductVariant
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth(): ?int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth(?int $depth): ProductVariant
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShippingRequired(): ?bool
    {
        return $this->shippingRequired;
    }

    /**
     * @param bool $shippingRequired
     */
    public function setShippingRequired(?bool $shippingRequired): ProductVariant
    {
        $this->shippingRequired = $shippingRequired;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(?int $width): ProductVariant
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getVolume(): ?int
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     */
    public function setVolume(?int $volume): ProductVariant
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight(?int $weight): ProductVariant
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Set taxRate
     *
     * @param TaxRateInterface $taxRate
     * @return ProductVariant
     */
    public function setTaxRate(TaxRateInterface $taxRate = null): ProductVariant
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * @return int
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): ProductVariant
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return TaxRateInterface
     */
    public function getTaxRate(): ?int
    {
        return $this->taxRate;
    }

    /**
     * @return bool
     */
    public function isStockTracked(): ?bool
    {
        return $this->stockTracked;
    }

    /**
     * @param bool $stockTracked
     */
    public function setStockTracked(bool $stockTracked): ProductVariant
    {
        $this->stockTracked = $stockTracked;

        return $this;
    }

    /**
     * @return ShippingCategoryInterface
     */
    public function getShippingCategory(): ?ShippingCategoryInterface
    {
        return $this->shippingCategory;
    }

    /**
     * @param ShippingCategoryInterface $shippingCategory
     * @return $this
     */
    public function setShippingCategory(ShippingCategoryInterface $shippingCategory): ProductVariant // This method is not required.
    {
        $this->shippingCategory = $shippingCategory;

        return $this;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return ProductVariant
     */
    public function setPrice($price): ProductVariant
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Get TaxRate
     *
     * @return integer
     */
    public function getTax(): ?float
    {
        return intval($this->getPrice() * $this->getProduct()->getTaxRate()->getAmount());
    }

    public function getUnitPriceTotal()
    {
        return $this->getTax() + $this->getPrice();
    }

    /**
     * @return FileInterface
     */
    public function getPicture(): ?FileInterface
    {
        return $this->picture;
    }

    /**
     * @param FileInterface $picture
     */
    public function setPicture($picture): ProductVariant
    {
        $this->picture = $picture;

        return $this;
    }
}
