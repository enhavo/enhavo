<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 27.08.20
 * Time: 14:38
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Product\Model\ProductAttribute as SyliusProductAttribute;

class ProductAttribute extends SyliusProductAttribute
{
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
