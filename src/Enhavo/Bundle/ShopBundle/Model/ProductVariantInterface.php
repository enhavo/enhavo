<?php
/**
 * ProductInterface.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Product\Model\ProductVariantInterface as SyliusProductVariantInterface;

interface ProductVariantInterface extends ResourceInterface, SyliusProductVariantInterface, ProductAccessInterface
{
    public function setTitle(?string $title): void;
    public function setDescription(?string $description): void;
    public function setPicture(?FileInterface $picture): void;
    public function setPictures(Collection $pictures): void;
    public function setPrice(?int $price): void;
    public function setReducedPrice(?int $reducedPrice): void;
    public function setReduced(bool $reduced): void;
    public function setShippingCategory(?ShippingCategoryInterface $shippingCategory): void;
    public function setShippingRequired(bool $shippingRequired): void;
    public function setTaxCategory(?TaxCategoryInterface $taxRate): void;
    public function setHeight(?float $height): void;
    public function setWidth(?float $width): void;
    public function setDepth(?float $depth): void;
    public function setVolume(?float $volume): void;
    public function setWeight(?float $weight): void;
    public function isOverridePrice(): bool;
    public function setOverridePrice(bool $overridePrice): void;
    public function isOverrideDescription(): bool;
    public function setOverrideDescription(bool $overrideDescription): void;
    public function isOverrideTaxCategory(): bool;
    public function setOverrideTaxCategory(bool $overrideTaxCategory): void;
    public function isOverrideDimensions(): bool;
    public function setOverrideDimensions(bool $overrideDimensions): void;
    public function isOverridePictures(): bool;
    public function setOverridePictures(bool $overridePictures): void;
    public function isOverrideShipping(): bool;
    public function setOverrideShipping(bool $overrideShipping): void;
    public function isDefault(): ?bool;
    public function setDefault(?bool $default): void;
    public function getSlug(): ?string;
    public function setSlug(?string $slug): void;
}
