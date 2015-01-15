<?php
/**
 * Route.php
 *
 * @since 01/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Model;

use Symfony\Component\Routing\Route as RoutingRoute;

class Route
{
    /**
     * @var string
     */
    private $routeName;

    /**
     * @var RoutingRoute
     */
    private $route;

    /**
     * @param \Symfony\Component\Routing\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return \Symfony\Component\Routing\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }
} 