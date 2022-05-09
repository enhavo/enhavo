<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemUnitInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sylius\Component\Order\Model\OrderItemUnit as BaseOrderItemUnit;
use Sylius\Component\Shipping\Model\ShippableInterface;

class OrderItemUnit extends BaseOrderItemUnit implements OrderItemUnitInterface
{
    private ?ShipmentInterface $shipment = null;

    public function getShipment(): ?ShipmentInterface
    {
        return $this->shipment;
    }

    public function setShipment(?ShipmentInterface $shipment): void
    {
        $this->shipment = $shipment;
    }

    public function getShippable(): ?ShippableInterface
    {
        /** @var OrderItemInterface $item */
        $item = $this->getOrderItem();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        // TODO: Implement getCreatedAt() method.
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt)
    {
        // TODO: Implement setCreatedAt() method.
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        // TODO: Implement getUpdatedAt() method.
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt)
    {
        // TODO: Implement setUpdatedAt() method.
    }
}
