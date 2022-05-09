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
    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order);
}
