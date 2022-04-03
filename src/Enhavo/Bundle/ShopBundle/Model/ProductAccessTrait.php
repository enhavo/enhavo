<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

trait ProductAccessTrait
{
    private ?string $title = null;
    private ?string $description;
    private ?FileInterface $picture;
    private Collection $pictures;
    private ?int $price;
    private ?int $reducedPrice;
    private ?bool $reduced = false;
    private ?ShippingCategoryInterface $shippingCategory;
    private ?TaxRateInterface $taxRate;
    private bool $shippingRequired = true;
    private ?float $height;
    private ?float $width;
    private ?float $depth;
    private ?float $volume;
    private ?float $weight;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPicture(): ?FileInterface
    {
        return $this->picture;
    }

    public function setPicture(?FileInterface $picture): void
    {
        $this->picture = $picture;
    }

    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function setPictures(Collection $pictures): void
    {
        $this->pictures = $pictures;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getReducedPrice(): ?int
    {
        return $this->reducedPrice;
    }

    public function setReducedPrice(?int $reducedPrice): void
    {
        $this->reducedPrice = $reducedPrice;
    }

    public function isReduced(): bool
    {
        return $this->reduced;
    }

    public function setReduced(bool $reduced): void
    {
        $this->reduced = $reduced;
    }

    public function getShippingCategory(): ?ShippingCategoryInterface
    {
        return $this->shippingCategory;
    }

    public function setShippingCategory(?ShippingCategoryInterface $shippingCategory): void
    {
        $this->shippingCategory = $shippingCategory;
    }

    public function getTaxRate(): ?TaxRateInterface
    {
        return $this->taxRate;
    }

    public function setTaxRate(?TaxRateInterface $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function isShippingRequired(): bool
    {
        return $this->shippingRequired;
    }

    public function setShippingRequired(bool $shippingRequired): void
    {
        $this->shippingRequired = $shippingRequired;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): void
    {
        $this->height = $height;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): void
    {
        $this->width = $width;
    }

    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(?float $depth): void
    {
        $this->depth = $depth;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): void
    {
        $this->volume = $volume;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }
}
