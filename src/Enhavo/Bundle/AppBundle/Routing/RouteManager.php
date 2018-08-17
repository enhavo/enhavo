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

    /**
     * @var array
     */
    private $autoGeneratorConfig;

    public function __construct(AutoGenerator $autoGenerator, $autoGeneratorConfig)
    {
        $this->autoGenerator = $autoGenerator;
        $this->autoGeneratorConfig = $autoGeneratorConfig;
    }

    public function updateRoute(RouteInterface $route)
    {
        $content = $route->getContent();
        $this->getAutoGeneraterOptions($content);

        $this->autoGenerator->generate($route);
    }

    public function updateRouteable(Routeable $routeable)
    {
        if($routeable->getRoute()) {
            $this->updateRoute($routeable->getRoute());
        }
    }

    /**
     * @param Routeable $content
     * @return RouteInterface[]
     */
    public function getRoutes(Routeable $content)
    {
        $type = $this->routeContentResolver->getType($content);
        return $this->em->getRepository('EnhavoAppBundle:Route')->findBy([
            'type' => $type,
            'typeId' => $content->getId()
        ]);
    }
}