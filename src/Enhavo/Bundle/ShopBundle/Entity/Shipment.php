<?php
/**
 * Shipment.php
 *
 * @since 08/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ShipmentInterface;
use Sylius\Component\Shipping\Model\Shipment as SyliusShipment;

class Shipment extends SyliusShipment implements ShipmentInterface
{
    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param OrderInterface $order
     * @return void
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }
}