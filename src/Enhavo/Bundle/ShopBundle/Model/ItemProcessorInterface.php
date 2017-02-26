<?php
/**
 * ItemProcessorInterface.php
 *
 * @since 26/02/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;


interface ItemProcessorInterface
{
    public function processItem(OrderItemInterface $orderItem);
}