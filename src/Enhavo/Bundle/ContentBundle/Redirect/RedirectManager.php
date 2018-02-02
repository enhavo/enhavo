<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 02.02.18
 * Time: 16:17
 */

namespace Enhavo\Bundle\ContentBundle\Redirect;

use Enhavo\Bundle\AppBundle\Route\RouteManager;
use Enhavo\Bundle\ContentBundle\Model\RedirectInterface;
use League\Uri\Schemes\Http;

class RedirectManager
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    public function __construct(RouteManager $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    public function update(RedirectInterface $redirect)
    {
        $this->checkFrom($redirect);
        $route = $redirect->getRoute();
        if($route === null) {
            $route = $this->routeManager->createRoute($redirect, $redirect->getFrom());
        }
        $route->setStaticPrefix($redirect->getFrom());
    }

    public function checkFrom(RedirectInterface $redirect)
    {
        $from = $redirect->getFrom();
        $uri = Http::createFromString($from);
        $from = $uri->withScheme('')->withHost('');
        $redirect->setFrom($from);
    }
}