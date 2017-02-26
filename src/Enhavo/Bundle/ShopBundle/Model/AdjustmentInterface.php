<?php
/**
 * AdjustmentInterface.php
 *
 * @since 26/02/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Core\Model\AdjustmentInterface as CoreAdjustmentInterface;

interface AdjustmentInterface extends CoreAdjustmentInterface
{
    const TAX_PROMOTION_ADJUSTMENT = 'tax_promotion';
}