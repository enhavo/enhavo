<?php
/**
 * ProcessorInterface.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;


interface ProcessorInterface
{
    public function process(OrderInterface $order);
}