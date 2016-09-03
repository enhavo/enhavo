<?php
/**
 * OrderFixedDisountAction.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;

class OrderFixedDiscountAction extends AbstractDiscountAction
{
    protected function configureAdjustmentAmount(AdjustmentInterface $adjustment, OrderInterface $subject, array $configuration)
    {
        $adjustment->setAmount((- $configuration['amount']));
    }
}