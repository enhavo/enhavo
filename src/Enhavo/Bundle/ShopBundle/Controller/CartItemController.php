<?php
/**
 * CartItemController.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Sylius\Bundle\OrderBundle\Controller\OrderItemController;

class CartItemController extends OrderItemController
{
    use CartSummaryTrait;
}
