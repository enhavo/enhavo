<?php

namespace Enhavo\Bundle\PageBundle\Routing;

use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PageCodeRouteCollector implements RouteCollectorInterface
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly Router $router,
    )
    {
    }

    public function getRouteCollection(array|string|null $groups = null): RouteCollection
    {
        $routeCollection = new RouteCollection();

        $pages = $this->pageRepository->findPublishedWithCode();
        foreach ($pages as $page) {
            $name = sprintf('enhavo_page_page_code_%s', Slugifier::slugify($page->getCode()));
            $route = new Route($this->router->generate($page));
            $routeCollection->add($name, $route);
        }

        return $routeCollection;
    }
}
