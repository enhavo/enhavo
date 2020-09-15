<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.08.20
 * Time: 11:20
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Product\Model\ProductOption as SyliusProductOption;

class ProductOption extends SyliusProductOption
{
    /** @var string */
    private $name;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
