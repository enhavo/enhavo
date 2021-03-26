<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.08.20
 * Time: 11:20
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Product\Model\ProductOptionValue as SyliusProductOptionValue;

class ProductOptionValue extends SyliusProductOptionValue
{
    /** @var integer|null */
    private $position;

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }
}
