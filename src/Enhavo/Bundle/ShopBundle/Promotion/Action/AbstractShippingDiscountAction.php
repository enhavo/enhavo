<?php
/**
 * AbstractShippingDiscountAction.php
 *
 * @since 12/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;


abstract class AbstractShippingDiscountAction extends AbstractDiscountAction
{
    protected function getAdjustmentType()
    {
        return \Sylius\Component\Core\Model\AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT;
    }
}