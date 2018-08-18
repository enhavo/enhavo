<?php
/**
 * RouteManager.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Routing;

use Enhavo\Bundle\AppBundle\Model\RouteInterface;

class RouteManager
{
    /**
     * @var AutoGenerator
     */
    protected $autoGenerator;

    public function __construct(AutoGenerator $autoGenerator)
    {
        $this->autoGenerator = $autoGenerator;
    }

    public function updateRoute(RouteInterface $route)
    {
        $this->autoGenerator->generate($route);
    }

    public function updateRouteable(Routeable $routeable)
    {
        $route = $routeable->getRoute();
        if($route) {
            $route->setContent($routeable);
            $this->updateRoute($route);
        }
    }
}