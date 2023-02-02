<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

trait ProductAccessTrait
{
    private ?string $title = null;
    private ?string $description = null;
    private ?FileInterface $picture = null;
    private Collection $pictures;
    private ?int $price = null;
    private ?int $reducedPrice = null;
    private ?bool $reduced = false;
    private ?ShippingCategoryInterface $shippingCategory;
    private ?TaxCategoryInterface $taxCategory;
    private bool $shippingRequired = true;
    private ?string $lengthUnit = null;
    private ?float $height = null;
    private ?float $width = null;
    private ?float $depth = null;
    private ?string $volumeUnit = null;
    private ?float $volume = null;
    private ?string $weightUnit = null;
    private ?float $weight = null;

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

    public function addPicture(FileInterface $picture): void
    {
        $this->pictures->add($picture);
    }

    public function removePicture(FileInterface $picture): void
    {
        $this->pictures->remove($picture);
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

    public function getTaxCategory(): ?TaxCategoryInterface
    {
        return $this->taxCategory;
    }

    public function setTaxCategory(?TaxCategoryInterface $taxCategory): void
    {
        $this->taxCategory = $taxCategory;
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

    public function getLengthUnit(): ?string
    {
        return $this->lengthUnit;
    }

    public function setLengthUnit(?string $lengthUnit): void
    {
        $this->lengthUnit = $lengthUnit;
    }

    public function getVolumeUnit(): ?string
    {
        return $this->volumeUnit;
    }

    public function setVolumeUnit(?string $volumeUnit): void
    {
        $this->volumeUnit = $volumeUnit;
    }

    public function getWeightUnit(): ?string
    {
        return $this->weightUnit;
    }

    public function setWeightUnit(?string $weightUnit): void
    {
        $this->weightUnit = $weightUnit;
    }
}
