<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 14.09.20
 * Time: 18:12
 */

/**
 * ProductAssociation.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Product\Model\ProductAssociationType as SyliusProductAssociationType;

class ProductAssociationType extends SyliusProductAssociationType
{
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
