<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

class ProductVariantProxy implements ProductVariantProxyInterface, PriceAccessInterface
{
    protected ProductInterface $product;
    protected ProductVariantInterface $productVariant;

    protected ?int $grossPrice = null;
    protected ?int $netPrice = null;

    public function __construct(ProductVariantInterface $productVariant)
    {
        $this->productVariant = $productVariant;
        $this->product = $productVariant->getProduct();
    }

    public function getId(): int
    {
        return $this->productVariant->getId();
    }

    public function getProductVariant(): ProductVariantInterface
    {
        return $this->productVariant;
    }

    public function getTitle(): ?string
    {
        return $this->productVariant->getTitle();
    }

    public function getFullTitle(): ?string
    {
        if ($this->productVariant->getTitle()) {
            return sprintf('%s %s', $this->product->getTitle(), $this->productVariant->getTitle());
        }
        return $this->product->getTitle();
    }

    public function getDescription(): ?string
    {
        return $this->productVariant->isOverrideDescription() ? $this->productVariant->getDescripion() : $this->product->getDescription();
    }

    public function getPicture(): ?FileInterface
    {
        return $this->productVariant->isOverridePictures() ? $this->productVariant->getPicture() : $this->product->getPicture();
    }

    public function getPictures(): Collection
    {
        return $this->productVariant->isOverridePictures() ? $this->productVariant->getPictures() : $this->product->getPictures();
    }

    public function getPrice(): ?int
    {
        return $this->productVariant->isOverridePrice() ? $this->productVariant->getPrice() : $this->product->getPrice();
    }

    public function getReducedPrice(): ?int
    {
        return $this->productVariant->isOverridePrice() ? $this->productVariant->getReducedPrice() : $this->product->getReducedPrice();
    }

    public function isReduced(): bool
    {
        return $this->productVariant->isOverridePrice() ? $this->productVariant->isReduced() : $this->product->isReduced();
    }

    public function getShippingCategory(): ?ShippingCategoryInterface
    {
        return $this->productVariant->isOverrideShipping() ? $this->productVariant->getShippingCategory() : $this->product->getShippingCategory();
    }

    public function isShippingRequired(): bool
    {
        return $this->productVariant->isOverrideShipping() ? $this->productVariant->isShippingRequired() : $this->product->isShippingRequired();
    }

    public function getTaxCategory(): ?TaxCategoryInterface
    {
        return $this->productVariant->isOverrideTaxCategory() ? $this->productVariant->getTaxCategory() : $this->product->getTaxCategory();
    }

    public function getHeight(): ?float
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getHeight() : $this->product->getHeight();
    }

    public function getWidth(): ?float
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getWidth() : $this->product->getWidth();
    }

    public function getDepth(): ?float
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getDepth() : $this->product->getDepth();
    }

    public function getVolume(): ?float
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getVolume() : $this->product->getVolume();
    }

    public function getWeight(): ?float
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getWeight() : $this->product->getWeight();
    }

    public function getLengthUnit(): ?string
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getLengthUnit() : $this->product->getLengthUnit();
    }

    public function getWeightUnit(): ?string
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getWeightUnit() : $this->product->getWeightUnit();
    }

    public function getVolumeUnit(): ?string
    {
        return $this->productVariant->isOverrideDimensions() ? $this->productVariant->getVolumeUnit() : $this->product->getVolumeUnit();
    }

    public function getGrossPrice(): ?int
    {
        return $this->grossPrice;
    }

    public function setGrossPrice(?int $grossPrice): void
    {
        $this->grossPrice = $grossPrice;
    }

    public function getNetPrice(): ?int
    {
        return $this->netPrice;
    }

    public function setNetPrice(?int $netPrice): void
    {
        $this->netPrice = $netPrice;
    }

    public function getTaxPrice(): ?int
    {
        return $this->getGrossPrice() - $this->getNetPrice();
    }

    public function getSlug()
    {
        return $this->productVariant->getSlug();
    }
}
