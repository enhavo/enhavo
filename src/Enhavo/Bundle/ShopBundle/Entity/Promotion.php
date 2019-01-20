<?php
/**
 * Promotion.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Promotion\Model\Promotion as SyliusPromotion;

class Promotion extends SyliusPromotion
{
    public function getOriginId()
    {
        return $this->id;
    }

    public function setOriginId($originId)
    {

    }

    public function getOriginType()
    {
        return self::class;
    }

    public function setOriginType($originType)
    {

    }
}
