<?php
/**
 * AdjustmentInterface.php
 *
 * @since 26/02/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Order\Model\AdjustmentInterface as SyliusAdjustmentInterface;

interface AdjustmentInterface extends SyliusAdjustmentInterface
{
    public const ORDER_ITEM_PROMOTION_ADJUSTMENT = 'order_item_promotion';
    public const ORDER_PROMOTION_ADJUSTMENT = 'order_promotion';
    public const ORDER_SHIPPING_PROMOTION_ADJUSTMENT = 'order_shipping_promotion';
    public const ORDER_UNIT_PROMOTION_ADJUSTMENT = 'order_unit_promotion';
    public const SHIPPING_ADJUSTMENT = 'shipping';
    public const TAX_ADJUSTMENT = 'tax';
    public const TAX_PROMOTION_ADJUSTMENT = 'tax_promotion';
    public const VOUCHER_ADJUSTMENT = 'voucher';
}
