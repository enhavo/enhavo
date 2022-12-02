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
use Sylius\Component\Product\Model\ProductInterface as SyliusProductInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;

interface ProductInterface extends ResourceInterface, SyliusProductInterface, ProductAccessInterface
{
    public function setTitle(?string $title): void;
    public function setDescription(?string $description): void;
    public function setPicture(?FileInterface $picture): void;
    public function setPictures(Collection $pictures): void;
    public function addPicture(FileInterface $picture): void;
    public function removePicture(FileInterface $picture): void;
    public function setPrice(?int $price): void;
    public function setReducedPrice(?int $reducedPrice): void;
    public function setReduced(bool $reduced): void;
    public function setShippingCategory(?ShippingCategoryInterface $shippingCategory): void;
    public function setShippingRequired(bool $shippingRequired): void;
    public function setTaxCategory(?TaxCategoryInterface $taxCategory): void;
    public function setHeight(?float $height): void;
    public function setWidth(?float $width): void;
    public function setDepth(?float $depth): void;
    public function setVolume(?float $volume): void;
    public function setWeight(?float $weight): void;
    public function getDefaultVariant();
}
