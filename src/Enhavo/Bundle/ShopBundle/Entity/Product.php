<?php
/**
 * Product.php
 *
 * @since 16/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Sylius\Component\Product\Model\Product as SyliusProduct;
use Sylius\Component\Taxation\Model\TaxRateInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;

class Product extends SyliusProduct implements ProductInterface, Routeable
{
    /** @var string */
    private $slug;

    /** @var string */
    private $title;

    /** @var boolean */
    private $active = true;

    /** @var \Doctrine\Common\Collections\Collection */
    private $pictures;

    /** @var FileInterface */
    private $picture;

    /** @var integer */
    private $price;

    /** @var integer */
    private $reducedPrice;

    /** @var boolean */
    private $reduced;

    /** @var ShippingCategoryInterface */
    private $shippingCategory;

    /** @var TaxRateInterface */
    private $taxRate;

    /** @var string */
    private $lengthUnit;

    /** @var integer */
    private $height;

    /** @var integer */
    private $width;

    /** @var integer */
    private $depth;

    /** @var string */
    private $volumeUnit;

    /** @var integer */
    private $volume;

    /** @var string */
    private $weightUnit;

    /** @var integer */
    private $weight;

    /** @var boolean */
    private $shippingRequired;

    /** @var Route */
    private $route;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pictures = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title): ProductInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return FileInterface
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param FileInterface $picture
     * @return self
     */
    public function setPicture($picture): ProductInterface
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Product
     */
    public function setPrice($price): ProductInterface
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
     * @return bool
     */
    public function isReduced(): ?bool
    {
        return $this->reduced;
    }

    /**
     * @param bool $reduced
     * @return self
     */
    public function setReduced(?bool $reduced): ProductInterface
    {
        $this->reduced = $reduced;

        return $this;
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
    public function setShippingCategory(ShippingCategoryInterface $shippingCategory): ProductInterface
    {
        $this->shippingCategory = $shippingCategory;

        return $this;
    }

    /**
     * Set taxRate
     *
     * @param TaxRateInterface $taxRate
     * @return Product
     */
    public function setTaxRate(TaxRateInterface $taxRate = null): ProductInterface
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
    public function setHeight(?int $height): ProductInterface
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
    public function setWidth(?int $width): ProductInterface
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
    public function setDepth(?int $depth): ProductInterface
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
    public function setVolume(?int $volume): ProductInterface
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return integer
     */
    public function getVolume(): ?int
    {
        return $this->volume;
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
     * @return self
     */
    public function setShippingRequired(bool $shippingRequired): ProductInterface
    {
        $this->shippingRequired = $shippingRequired;

        return $this;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Product
     */
    public function setWeight($weight): ProductInterface
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
        $rate = $this->getTaxRate() ? $this->getTaxRate()->getAmount() : 0;
        return intval($this->getPrice() * $rate);
    }

    public function getUnitPriceTotal()
    {
        return $this->getTax() + $this->getPrice();
    }

    public function __toString(): string
    {
        if($this->title === null) {
            return '';
        }
        return $this->title;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): ProductInterface
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->getTitle();
    }

    public function setName(?string $name): void
    {
        $this->setTitle($name);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(?string $slug = null): void
    {
        $this->slug = $slug;
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
     * @return self
     */
    public function setActive(?bool $active): ProductInterface
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Add picture
     *
     * @param FileInterface $picture
     * @return Product
     */
    public function addPicture(FileInterface $picture): ProductInterface
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

    /**
     * @return int
     */
    public function getReducedPrice(): ?int
    {
        return $this->reducedPrice;
    }

    /**
     * @param int $reducedPrice
     * @return self
     */
    public function setReducedPrice(?int $reducedPrice): ProductInterface
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getLengthUnit(): ?string
    {
        return $this->lengthUnit;
    }

    /**
     * @param string $lengthUnit
     * @return self
     */
    public function setLengthUnit(?string $lengthUnit): ProductInterface
    {
        $this->lengthUnit = $lengthUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getVolumeUnit(): ?string
    {
        return $this->volumeUnit;
    }

    /**
     * @param string $volumeUnit
     * @return self
     */
    public function setVolumeUnit(?string $volumeUnit): ProductInterface
    {
        $this->volumeUnit = $volumeUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeightUnit(): ?string
    {
        return $this->weightUnit;
    }

    /**
     * @param string $weightUnit
     * @return self
     */
    public function setWeightUnit(?string $weightUnit): ProductInterface
    {
        $this->weightUnit = $weightUnit;

        return $this;
    }

    public function getDefaultVariant(): ?ProductVariant
    {
        $variants = $this->getEnabledVariants();
        if (!$variants->isEmpty()) {
            return $variants->get(0);
        }
        return null;
    }
}
