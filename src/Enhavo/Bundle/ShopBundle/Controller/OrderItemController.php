<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceControllerTrait;
use Sylius\Bundle\OrderBundle\Controller\OrderItemController as BaseOrderItemController;

class OrderItemController extends BaseOrderItemController
{
    use ResourceControllerTrait;
}
