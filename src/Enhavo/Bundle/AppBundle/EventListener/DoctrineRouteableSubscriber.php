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
use Enhavo\Bundle\AppBundle\Route\RouteContentResolver;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Symfony\Component\EventDispatcher\GenericEvent;

class DoctrineRouteableSubscriber
{
    /**
     * @var RouteContentResolver
     */
    protected $resolver;

    /**
     * @var object
     */
    protected $content;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(RouteContentResolver $resolver, EntityManager $entityManager)
    {
        $this->resolver = $resolver;
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
            $content = $route->getContent();
            $route->setTypeId($content->getId());
            $route->setType($this->resolver->getType($content));
            $route->setName(sprintf('dynamic_route_%s', $route->getId()));
            $this->entityManager->flush();
        }
    }
}
