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
use Enhavo\Bundle\AppBundle\Route\RouteContentResolver;
use Enhavo\Bundle\AppBundle\Entity\Route;

class DoctrineRouteSubscriber implements EventSubscriber
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
            'postLoad'
        );
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