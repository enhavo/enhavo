<?php
/**
 * DoctrineRouteContentSubscriber.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Route\RouteContentResolver;
use Enhavo\Bundle\AppBundle\Entity\Route;

class DoctrineRouteableSubscriber implements EventSubscriber
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

    public function __construct(RouteContentResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate'
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Routeable && $entity->getRoute() != null) {
            $this->updateRoute($entity->getRoute());
            $args->getEntityManager()->flush();
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Routeable && $entity->getRoute() != null) {
            $this->updateRoute($entity->getRoute());
            $args->getEntityManager()->flush();
        }
    }

    public function updateRoute(Route $route)
    {
        $content = $route->getContent();
        $route->setTypeId($content->getId());
        $route->setType($this->resolver->getType($content));
        $route->setName(sprintf('dynamic_route_%s', $route->getId()));
    }
}