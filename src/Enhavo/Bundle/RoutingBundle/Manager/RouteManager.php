<?php
/**
 * RouteManager.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Manager;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AutoGenerator;
use Sylius\Component\Resource\Factory\FactoryInterface;

class RouteManager
{
    /** @var AutoGenerator */
    private $autoGenerator;

    /** @var FactoryInterface */
    private $routeFactory;

    public function __construct(AutoGenerator $autoGenerator, FactoryInterface $routeFactory)
    {
        $this->autoGenerator = $autoGenerator;
        $this->routeFactory = $routeFactory;
    }

    public function update($resource)
    {
        if ($resource instanceof Routeable) {
            if ($resource->getRoute() === null) {
                /** @var Route $route */
                $route = $this->routeFactory->createNew();
                $resource->setRoute($route);
            }

            $route = $resource->getRoute();
            $route->setContent($resource);
        }

        $this->autoGenerator->generate($resource);
    }
}
