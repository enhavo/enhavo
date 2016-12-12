<?php
/**
 * RouteManager.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Entity\Route;

class RouteManager
{
    /**
     * @var RouteContentResolver
     */
    protected $routeContentResolver;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(RouteContentResolver $routeContentResolver, EntityManagerInterface $em)
    {
        $this->routeContentResolver = $routeContentResolver;
        $this->em = $em;
    }

    public function update(Route $route)
    {
        $content = $route->getContent();
        $route->setTypeId($content->getId());
        $route->setType($this->routeContentResolver->getType($content));
        $route->setName(sprintf('dynamic_route_%s', $route->getId()));
    }

    public function getRoutes(Routeable $content)
    {
        $type = $this->routeContentResolver->getType($content);
        return $this->em->getRepository('EnhavoAppBundle:Route')->findBy([
            'type' => $type,
            'typeId' => $content->getId()
        ]);
    }

    public function createRoute(Routeable $content, $staticPrefix)
    {
        $route = new Route();
        $route->setStaticPrefix($staticPrefix);
        $route->setContent($content);
        $this->update($route);
        $content->setRoute($route);
        return $route;
    }
}