<?php
/**
 * ShipmentInterface.php
 *
 * @since 08/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Taxation\Model\TaxableInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

interface ShippingMethodInterface extends TaxableInterface
{
    public function setTaxCategory(?TaxCategoryInterface $taxCategory): void;
    public function getName(): ?string;
    public function setName(?string $name): void;
}
