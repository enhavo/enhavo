<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.20
 * Time: 16:32
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Sylius\Component\Product\Model\ProductVariant as SyliusProductVariant;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;

class ProductVariant extends SyliusProductVariant
//    implements Routeable
{
//    /**
//     * @var Route
//     */
//    private $route;
//
//    public function getRoute()
//    {
//        return $this->route;
//    }
//
//    public function setRoute(RouteInterface $route)
//    {
//        $this->route = $route;
//    }
}
