<?php
/**
 * Routeable.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;


use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

interface Routeable
{
    /**
     * @return RouteInterface
     */
    public function getRoute();
    public function setRoute(RouteObjectInterface $route);
}