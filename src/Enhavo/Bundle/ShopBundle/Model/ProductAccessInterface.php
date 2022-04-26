<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxableInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

interface ProductAccessInterface extends TaxableInterface
{
    public function getId();
    public function getTitle(): ?string;
    public function getDescription(): ?string;
    public function getPicture(): ?FileInterface;
    public function getPictures(): Collection;
    public function getPrice(): ?int;
    public function getReducedPrice(): ?int;
    public function isReduced(): bool;
    public function getShippingCategory(): ?ShippingCategoryInterface;
    public function isShippingRequired(): bool;
    public function getHeight(): ?float;
    public function getWidth(): ?float;
    public function getDepth(): ?float;
    public function getVolume(): ?float;
    public function getWeight(): ?float;
}
