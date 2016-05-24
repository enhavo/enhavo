<?php
/**
 * Routeable.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;


use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\Routing\RouterInterface;

interface Routeable
{
    public function getRoute();
    public function setRoute(RouteObjectInterface $route);
}