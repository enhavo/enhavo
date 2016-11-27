<?php
/**
 * DoctrineRouteContentSubscriber.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Route\RouteManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class DoctrineRouteableSubscriber
{
    /**
     * @var object
     */
    protected $content;

    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(RouteManager $routeManager, EntityManager $entityManager)
    {
        $this->routeManager = $routeManager;
        $this->entityManager = $entityManager;
    }

    public function onCreate(GenericEvent $event) {
        $entity = $event->getSubject();

        if ($entity instanceof Routeable && $entity->getRoute() != null) {
            $this->updateRoute($entity->getRoute());
        }
    }

    public function onUpdate(GenericEvent $event) {
        $entity = $event->getSubject();

        if ($entity instanceof Routeable && $entity->getRoute() != null) {
            $this->updateRoute($entity->getRoute());
        }
    }

    public function updateRoute(Route $route)
    {
        if (!$route->getTypeId()) {
            $this->routeManager->update($route);
            $this->entityManager->flush();
        }
    }
}
