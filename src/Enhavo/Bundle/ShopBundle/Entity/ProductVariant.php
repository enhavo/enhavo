<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.20
 * Time: 16:32
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Product\Model\ProductVariant as SyliusProductVariant;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

class ProductVariant extends SyliusProductVariant
{
    /**
     * @var boolean
     */
    private $active = true;

    /**
     * @var string
     */
    private $title;

    /**
     * @var FileInterface
     */
    private $picture;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pictures;

    /**
     * @var integer
     */
    private $price;

    /**
     * @var integer
     */
    private $reducedPrice;

    /**
     * @var boolean
     */
    private $reduced;

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
    private $stockTracked = false;

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
     * ProductVariant constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->position = 0;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(?bool $active): ProductVariantInterface
    {
        $this->active = $active;

        return $this;
    }

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
    public function setTitle($title): ProductVariantInterface
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
    public function setHeight(?int $height): ProductVariantInterface
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
    public function setDepth(?int $depth): ProductVariantInterface
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
    public function setShippingRequired(?bool $shippingRequired): ProductVariantInterface
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
    public function setWidth(?int $width): ProductVariantInterface
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
    public function setVolume(?int $volume): ProductVariantInterface
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
    public function setWeight(?int $weight): ProductVariantInterface
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
    public function setTaxRate(TaxRateInterface $taxRate = null): ProductVariantInterface
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
    public function setStock(int $stock): ProductVariantInterface
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReduced(): ?bool
    {
        return $this->reduced;
    }

    /**
     * @param bool $reduced
     */
    public function setReduced(?bool $reduced): ProductVariantInterface
    {
        $this->reduced = $reduced;

        return $this;
    }

    /**
     * @return int
     */
    public function getReducedPrice(): ?int
    {
        return $this->reducedPrice;
    }

    /**
     * @param int $reducedPrice
     */
    public function setReducedPrice(?int $reducedPrice): ProductVariantInterface
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return TaxRateInterface
     */
    public function getTaxRate(): ?TaxRateInterface
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
    public function setStockTracked(bool $stockTracked): ProductVariantInterface
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
    public function setShippingCategory(ShippingCategoryInterface $shippingCategory): ProductVariantInterface // This method is not required.
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
    public function setPrice($price): ProductVariantInterface
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
    public function setPicture($picture): ProductVariantInterface
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Add picture
     *
     * @param FileInterface $picture
     * @return Product
     */
    public function addPicture(FileInterface $picture): ProductVariantInterface
    {
        $this->pictures[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param FileInterface $picture
     */
    public function removePicture(FileInterface $picture): void
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }
}
