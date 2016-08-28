<?php
/**
 * ProductInterface.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

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