<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.08.20
 * Time: 11:20
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Sylius\Component\Product\Model\ProductOption as SyliusProductOption;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;

class ProductOption extends SyliusProductOption implements Routeable
{
    /**
     * @var Route
     */
    private $route;

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;
    }
}
