<?php
/**
 * AdminUserBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\UserBundle\EventListener;

use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use esperanto\AdminBundle\Event\MenuBuilderEvent;

class AdminUserBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_user.user.build_menu' => array('onBuildMenu', 0),
            'esperanto_user.user.build_table_route' => array('onBuildTableRoute', 0),
        );
    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {

    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoUserBundle:User:table.html.twig');
    }
}