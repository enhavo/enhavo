<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Routing;

use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\AppBundle\Routing\RouteCollectorTrait;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PageSpecialRouteCollector implements RouteCollectorInterface
{
    use RouteCollectorTrait;

    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly Router $router,
    ) {
    }

    public function getRouteCollection(array|string|bool|null $groups = null): RouteCollection
    {
        $routeCollection = new RouteCollection();

        if (!$this->inGroup('theme', $groups)) {
            return $routeCollection;
        }

        $pages = $this->pageRepository->findPublishedSpecials();
        foreach ($pages as $page) {
            $name = sprintf('enhavo_page_page_special_%s', Slugifier::slugify($page->getSpecial()));
            $route = new Route($this->router->generate($page));
            $routeCollection->add($name, $route);
        }

        return $routeCollection;
    }
}
