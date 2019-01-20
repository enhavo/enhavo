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
    const TAX_PROMOTION_ADJUSTMENT = 'tax_promotion';
}
