<?php
/**
 * DoctrineRouteContentSubscriber.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\EventListener;

use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Manager\RouteManager;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
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
            'enhavo_app.pre_create' => 'preSave',
            'enhavo_app.pre_update' => 'preSave'
        );
    }

    public function preSave(ResourceControllerEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof Routeable) {
            $this->routeManager->updateRouteable($resource);
        }
    }
}
