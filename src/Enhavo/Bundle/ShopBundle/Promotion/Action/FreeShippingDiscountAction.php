<?php
/**
 * FreeShippingAction.php
 *
 * @since 12/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;

class FreeShippingDiscountAction extends AbstractShippingDiscountAction
{
    protected function configureAdjustmentAmount(AdjustmentInterface $adjustment, OrderInterface $subject, array $configuration)
    {
        $adjustment->setAmount(- $subject->getShippingTotal());
    }
}