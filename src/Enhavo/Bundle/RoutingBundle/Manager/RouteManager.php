<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Manager;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AutoGenerator;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;

class RouteManager
{
    public function __construct(
        private readonly AutoGenerator $autoGenerator,
        private readonly FactoryInterface $routeFactory,
        private readonly EntityManager $em,
    ) {
    }

    public function update($resource)
    {
        if ($resource instanceof Routeable) {
            if (null === $resource->getRoute()) {
                /** @var Route $route */
                $route = $this->routeFactory->createNew();
                $resource->setRoute($route);
            }

            $route = $resource->getRoute();
            $route->setContent($resource);

            if (empty($route->getName())) {
                $route->generateRouteName();
            }
        }

        $this->autoGenerator->generate($resource);

        if ($resource instanceof Routeable) {
            $route = $resource->getRoute();
            if ($route && empty($route->getStaticPrefix())) {
                $resource->setRoute(null);
                $this->em->remove($route);
            }
        }
    }
}
