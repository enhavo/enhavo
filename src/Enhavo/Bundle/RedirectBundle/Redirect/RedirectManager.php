<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RedirectBundle\Redirect;

use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Manager\RouteManager;
use League\Uri\Http;

class RedirectManager
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    /**
     * RedirectManager constructor.
     */
    public function __construct(RouteManager $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    public function update(RedirectInterface $redirect)
    {
        $this->checkFrom($redirect);
        $route = $redirect->getRoute();
        if (null === $route) {
            $route = new Route();
            $redirect->setRoute($route);
        }
        $route->setStaticPrefix($redirect->getFrom());

        if (null === $route->getName()) {
            $route->setName($this->getRandomName());
        }

        $route->setPosition(-1);

        $this->routeManager->update($redirect);
    }

    public function checkFrom(RedirectInterface $redirect)
    {
        $from = $redirect->getFrom();
        $uri = Http::createFromString($from);
        $from = $uri->withScheme('')->withHost('');
        $redirect->setFrom($from);
    }

    protected function getRandomName()
    {
        return 'r'.uniqid();
    }
}
