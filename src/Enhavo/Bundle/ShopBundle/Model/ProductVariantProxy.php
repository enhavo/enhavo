<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

class ProductVariantProxy implements ProductVariantProxyInterface
{
    protected ProductInterface $product;
    protected ProductVariantInterface $productVariant;

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
        return $this->productVariant->getTitle() ?? $this->product->getTitle();
    }

    public function getDescription(): ?string
    {
        return $this->productVariant->getDescription() ?? $this->product->getDescription();
    }

    public function getPicture(): ?FileInterface
    {
        return $this->productVariant->getPicture() ?? $this->product->getPicture();
    }

    public function getPictures(): Collection
    {
        return $this->productVariant->getPictures() ?? $this->product->getPictures();
    }

    public function getPrice(): ?int
    {
        return $this->productVariant->getPrice() ? $this->productVariant->getPrice() : $this->product->getPrice();
    }

    public function getReducedPrice(): ?int
    {
        return $this->productVariant->getPrice() ?? $this->product->getPrice();
    }

    public function isReduced(): bool
    {
        return $this->productVariant->getPrice() ?? $this->product->getPrice();
    }

    public function getShippingCategory(): ?ShippingCategoryInterface
    {
        return $this->productVariant->getShippingCategory() ?? $this->product->getShippingCategory();
    }

    public function getTaxCategory(): ?TaxCategoryInterface
    {
        return $this->productVariant->getTaxCategory() ?? $this->product->getTaxCategory();
    }

    public function isShippingRequired(): bool
    {
        return $this->productVariant->isShippingRequired() ?? $this->product->isShippingRequired();
    }

    public function getHeight(): ?float
    {
        return $this->productVariant->getHeight() ?? $this->product->getHeight();
    }

    public function getWidth(): ?float
    {
        return $this->productVariant->getWidth() ?? $this->product->getWidth();
    }

    public function getDepth(): ?float
    {
        return $this->productVariant->getDepth() ?? $this->product->getDepth();
    }

    public function getVolume(): ?float
    {
        return $this->productVariant->getVolume() ?? $this->product->getVolume();
    }

    public function getWeight(): ?float
    {
        return $this->productVariant->getWeight() ?? $this->product->getWeight();
    }
}
