<?php
/**
 * OrderTaxProcessor.php
 *
 * @since 26/02/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\ItemProcessorInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;
use Doctrine\Common\Proxy\Proxy;

class OrderTaxProcessor implements ProcessorInterface, ItemProcessorInterface
{
    /**
     * @var FactoryInterface
     */
    protected $adjustmentFactory;

    /**
     * OrderTaxProcessor constructor.
     *
     * @param FactoryInterface $adjustmentFactory
     */
    public function __construct(FactoryInterface $adjustmentFactory)
    {
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order)
    {
        $items = $order->getItems();
        foreach($items as $orderItem) {
            $this->processItem($orderItem);
        }

//        /** @var AdjustmentInterface $adjustment */
//        $adjustment = $this->adjustmentFactory->createNew();
//        $adjustment->setType('tax');
//        $adjustment->setAmount($orderItem->getProduct()->getTax());
//        $taxRate = $orderItem->getProduct()->getTaxRate();
//        $taxClass = get_class($taxRate);
//        if($taxRate instanceof Proxy) {
//            $taxClass = get_parent_class($taxRate);
//        }
//        $adjustment->setOriginType($taxClass);
//        $adjustment->setOriginId($taxRate->getId());
//        $unit->addAdjustment($adjustment);
    }

    public function processItem(OrderItemInterface $orderItem)
    {
        $taxRate = $orderItem->getProduct()->getTaxRate();
        $taxClass = get_class($taxRate);
        if($taxRate instanceof Proxy) {
            $taxClass = get_parent_class($taxRate);
        }

        $orderItem->getUnitPrice();

        $amount = $orderItem->getProduct()->getTax();

        $units = $orderItem->getUnits();
        foreach($units as $unit) {
            $adjustments = $unit->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
            if($adjustments->isEmpty()) {
                /** @var AdjustmentInterface $adjustment */
                $adjustment = $this->adjustmentFactory->createNew();
                $adjustment->setType('tax');
                $adjustment->setOriginType($taxClass);
                $adjustment->setOriginId($taxRate->getId());
                $unit->addAdjustment($adjustment);
            } else {
                $adjustment = $adjustments->get(0);
            }

            $adjustment->setAmount($amount);
        }
    }
}
