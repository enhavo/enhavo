<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\SliderBundle\EventListener;

use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use esperanto\AdminBundle\Builder\Route\SyliusRouteBuilder;
use esperanto\AdminBundle\Builder\View\ViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;

class AdminSliderBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_slider.slider.build_menu' => array('onBuildMenu', 0),
            'esperanto_slider.slider.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_slider.slider.build_index_route' => array('onBuildIndexRoute', 0),
        );
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {

    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {

    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {
        $event->setBuilder(null);
    }
}