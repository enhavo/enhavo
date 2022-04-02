<?php
/**
 * ProductInterface.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxation\Model\TaxRateInterface;
use Sylius\Component\Product\Model\ProductInterface as SyliusProductInterface;

interface ProductVariantInterface extends ResourceInterface, SyliusProductInterface
{
    public function getPrice(): ?int;
    public function getTax(): ?int;
    public function getTaxRate(): ?TaxRateInterface;
    public function getPicture(): ?FileInterface;
}
