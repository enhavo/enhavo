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

interface ProductInterface extends ResourceInterface, SyliusProductInterface
{
    /**
     * @return integer
     */
    public function getPrice();

    /**
     * @return integer
     */
    public function getTax();

    /**
     * @return TaxRateInterface
     */
    public function getTaxRate();

    /**
     * @return FileInterface|null
     */
    public function getPicture();
}
