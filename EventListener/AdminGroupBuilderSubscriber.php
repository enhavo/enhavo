<?php
/**
 * AdminGroupBuilderSubscriber.php
 *
 * @since 21/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\UserBundle\EventListener;

use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use esperanto\AdminBundle\Event\MenuBuilderEvent;

class AdminGroupBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_user.group.build_menu' => array('onBuildMenu', 0),
            'esperanto_user.group.build_table_route' => array('onBuildTableRoute', 0),
        );
    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {

    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoUserBundle:Group:table.html.twig');
    }
}