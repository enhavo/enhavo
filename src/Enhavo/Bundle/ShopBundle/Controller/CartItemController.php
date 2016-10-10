<?php
/**
 * CartItemController.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Sylius\Bundle\CartBundle\Controller\CartItemController as SyliusCartItemController;

class CartItemController extends SyliusCartItemController
{
    use CartSummaryTrait;
}