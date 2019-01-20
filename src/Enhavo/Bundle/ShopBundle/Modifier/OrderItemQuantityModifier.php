<?php

namespace Enhavo\Bundle\ShopBundle\Modifier;

use Doctrine\Common\Persistence\Proxy;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class OrderItemQuantityModifier implements OrderItemQuantityModifierInterface
{
    /**
     * @var OrderItemUnitFactoryInterface
     */
    private $orderItemUnitFactory;

    /**
     * @var AdjustmentFactoryInterface
     */
    protected $adjustmentFactory;

    /**
     * OrderItemQuantityModifier constructor.
     *
     * @param OrderItemUnitFactoryInterface $orderItemUnitFactory
     * @param FactoryInterface $adjustmentFactory
     */
    public function __construct(OrderItemUnitFactoryInterface $orderItemUnitFactory, FactoryInterface $adjustmentFactory)
    {
        $this->orderItemUnitFactory = $orderItemUnitFactory;
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(OrderItemInterface $orderItem, int $targetQuantity): void
    {
        $currentQuantity = $orderItem->getQuantity();
        if (0 >= $targetQuantity || $currentQuantity === $targetQuantity) {
            return;
        }

        if ($targetQuantity < $currentQuantity) {
            $this->decreaseUnitsNumber($orderItem, $currentQuantity - $targetQuantity);
        } elseif ($targetQuantity > $currentQuantity) {
            $this->increaseUnitsNumber($orderItem, $targetQuantity - $currentQuantity);
        }
    }

    /**
     * @param OrderItemInterface $orderItem
     * @param int $increaseBy
     */
    private function increaseUnitsNumber(OrderItemInterface $orderItem, $increaseBy)
    {
        for ($i = 0; $i < $increaseBy; ++$i) {
            $unit = $this->orderItemUnitFactory->createForItem($orderItem);

            if($orderItem instanceof OrderItem) {
                /** @var AdjustmentInterface $adjustment */
                $adjustment = $this->adjustmentFactory->createNew();
                $adjustment->setType('tax');
                $adjustment->setAmount($orderItem->getProduct()->getTax());
                $taxRate = $orderItem->getProduct()->getTaxRate();
                $taxClass = get_class($taxRate);
                if($taxRate instanceof Proxy) {
                    $taxClass = get_parent_class($taxRate);
                }
                $adjustment->setOriginType($taxClass);
                $adjustment->setOriginId($taxRate->getId());
                $unit->addAdjustment($adjustment);
            }
        }
    }

    /**
     * @param OrderItemInterface $orderItem
     * @param int $decreaseBy
     */
    private function decreaseUnitsNumber(OrderItemInterface $orderItem, $decreaseBy)
    {
        foreach ($orderItem->getUnits() as $unit) {
            if (0 >= $decreaseBy--) {
                break;
            }

            $orderItem->removeUnit($unit);
        }
    }
}
