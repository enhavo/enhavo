<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

interface ProductAccessInterface
{
    public function getTitle(): ?string;
    public function setTitle(?string $title): void;
    public function getDescription(): ?string;
    public function setDescription(?string $description): void;
    public function getPicture(): ?FileInterface;
    public function setPicture(?FileInterface $picture): void;
    public function getPictures(): Collection;
    public function setPictures(Collection $pictures): void;
    public function getPrice(): ?int;
    public function setPrice(?int $price): void;
    public function getReducedPrice(): ?int;
    public function setReducedPrice(?int $reducedPrice): void;
    public function isReduced(): bool;
    public function setReduced(bool $reduced): void;
    public function getShippingCategory(): ?ShippingCategoryInterface;
    public function setShippingCategory(?ShippingCategoryInterface $shippingCategory): void;
    public function getTaxRate(): ?TaxRateInterface;
    public function setTaxRate(?TaxRateInterface $taxRate): void;
    public function isShippingRequired(): bool;
    public function setShippingRequired(bool $shippingRequired): void;
    public function getHeight(): ?float;
    public function setHeight(?float $height): void;
    public function getWidth(): ?float;
    public function setWidth(?float $width): void;
    public function getDepth(): ?float;
    public function setDepth(?float $depth): void;
    public function getVolume(): ?float;
    public function setVolume(?float $volume): void;
    public function getWeight(): ?float;
    public function setWeight(?float $weight): void;
}
