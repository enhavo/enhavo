<?php
/**
 * DoctrineRouteContentSubscriber.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Enhavo\Bundle\AdminBundle\Route\RouteContentResolver;
use Enhavo\Bundle\AdminBundle\Entity\Route;

class DoctrineRouteContentSubscriber implements EventSubscriber
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
            'preUpdate',
            'postLoad'
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        /**
         * We temporary save the content object and
         * route to wait until the content is persist
         * and have an id. Then we can save the id
         * inside the route
         */
        if ($entity instanceof Route) {
            $this->route = $entity;
            $this->content = $entity->getContent();
        }

        if($this->content === $entity) {
            $this->updateRoute($this->route);
            $args->getEntityManager()->flush();
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Route) {
            $this->updateRoute($entity);
        }
    }

    public function updateRoute(Route $route)
    {
        $content = $route->getContent();
        $route->setTypeId($content->getId());
        $route->setType($this->resolver->getType($content));
        $route->setName(sprintf('dynamic_route_%s', $route->getId()));
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Route) {
            $repository = $this->resolver->getRepository($entity->getType());
            if(!empty($repository)) {
                $content = $repository->find($entity->getTypeId());
                $entity->setContent($content);
            }
        }
    }
}