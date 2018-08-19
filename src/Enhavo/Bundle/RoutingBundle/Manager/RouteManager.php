<?php
/**
 * RouteManager.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Manager;

use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AutoGenerator;

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

    public function update($resource)
    {
        if($resource instanceof Routeable && $resource->getRoute()) {
            $resource->getRoute()->setContent($resource);
        }

        $this->autoGenerator->generate($resource);
    }
}