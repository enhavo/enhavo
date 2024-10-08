<?php
/**
 * DoctrineRouteContentSubscriber.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Enhavo\Bundle\RoutingBundle\Manager\RouteManager;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RouteableListener implements EventSubscriberInterface
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    public function __construct(RouteManager $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ResourceEvents::PRE_CREATE => 'preSave',
            ResourceEvents::PRE_UPDATE => 'preSave'
        );
    }

    public function preSave(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof Routeable || $resource instanceof Slugable) {
            $this->routeManager->update($resource);
        }
    }
}
