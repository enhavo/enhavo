<?php
/**
 * ShipmentInterface.php
 *
 * @since 08/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Shipping\Model\ShipmentInterface as SyliusShipmentInterface;

interface ShipmentInterface extends SyliusShipmentInterface
{
    /**
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * @param OrderInterface $order
     * @return mixed
     */
    public function setOrder(OrderInterface $order);
}