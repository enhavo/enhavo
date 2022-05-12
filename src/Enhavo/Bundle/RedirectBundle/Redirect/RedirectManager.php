<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 02.02.18
 * Time: 16:17
 */

namespace Enhavo\Bundle\RedirectBundle\Redirect;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Manager\RouteManager;
use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use League\Uri\Http;

class RedirectManager
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    /**
     * RedirectManager constructor.
     * @param RouteManager $routeManager
     */
    public function __construct(RouteManager $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    public function update(RedirectInterface $redirect)
    {
        $this->checkFrom($redirect);
        $route = $redirect->getRoute();
        if($route === null) {
            $route = new Route();
            $redirect->setRoute($route);
        }
        $route->setStaticPrefix($redirect->getFrom());

        if ($route->getName() === null) {
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
        return 'r' . uniqid();
    }
}
