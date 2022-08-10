<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.20
 * Time: 16:32
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessTrait;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\StockAccessInterface;
use Sylius\Component\Product\Model\ProductVariant as SyliusProductVariant;

class ProductVariant extends SyliusProductVariant implements ProductVariantInterface, StockAccessInterface
{
    use ProductAccessTrait;

    private ?int $stock = null;
    private bool $stockTracked = false;
    private ?bool $default = false;
    private bool $index = false;
    private ?string $slug = null;
    private bool $overrideDescription = false;
    private bool $overridePictures = false;
    private bool $overridePrice = false;
    private bool $overrideShipping = false;
    private bool $overrideTaxCategory = false;
    private bool $overrideDimensions = false;

    public function __construct()
    {
        parent::__construct();
        $this->position = 0;
        $this->pictures = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->getTitle();
    }

    public function setName(?string $name): void
    {
        $this->setTitle($name);
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }

    public function isStockTracked(): bool
    {
        return $this->stockTracked;
    }

    public function setStockTracked(bool $stockTracked): void
    {
        $this->stockTracked = $stockTracked;
    }

    public function isDefault(): ?bool
    {
        return $this->default;
    }

    public function setDefault(?bool $default): void
    {
        $this->default = $default;
    }

    public function isIndex(): bool
    {
        return $this->index;
    }

    public function setIndex(?bool $index): void
    {
        $this->index = $index;
    }

    public function isOverridePrice(): bool
    {
        return $this->overridePrice;
    }

    public function setOverridePrice(bool $overridePrice): void
    {
        $this->overridePrice = $overridePrice;
    }

    public function isOverrideDescription(): bool
    {
        return $this->overrideDescription;
    }

    public function setOverrideDescription(bool $overrideDescription): void
    {
        $this->overrideDescription = $overrideDescription;
    }

    public function isOverrideTaxCategory(): bool
    {
        return $this->overrideTaxCategory;
    }

    public function setOverrideTaxCategory(bool $overrideTaxCategory): void
    {
        $this->overrideTaxCategory = $overrideTaxCategory;
    }

    public function isOverrideDimensions(): bool
    {
        return $this->overrideDimensions;
    }

    public function setOverrideDimensions(bool $overrideDimensions): void
    {
        $this->overrideDimensions = $overrideDimensions;
    }

    public function isOverridePictures(): bool
    {
        return $this->overridePictures;
    }

    public function setOverridePictures(bool $overridePictures): void
    {
        $this->overridePictures = $overridePictures;
    }

    public function isOverrideShipping(): bool
    {
        return $this->overrideShipping;
    }

    public function setOverrideShipping(bool $overrideShipping): void
    {
        $this->overrideShipping = $overrideShipping;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }
}
