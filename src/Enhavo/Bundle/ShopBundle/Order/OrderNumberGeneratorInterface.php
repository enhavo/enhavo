<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 29.12.16
 * Time: 09:36
 */

namespace Enhavo\Bundle\ShopBundle\Order;


interface OrderNumberGeneratorInterface
{
    public function generate();
}