<?php
/**
 * ProductInterface.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Cart\Model\CartItemInterface;

interface ProductInterface
{
    /**
     * @return integer
     */
    public function getPrice();

    /**
     * @return integer
     */
    public function getTax();
}