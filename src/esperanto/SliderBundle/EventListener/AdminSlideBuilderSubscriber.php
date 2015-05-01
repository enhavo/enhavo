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
use esperanto\AdminBundle\Event\MenuBuilderEvent;

class AdminSlideBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_slider.slide.build_menu' => array('onBuildMenu', 1),
            'esperanto_slider.slide.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_slider.slide.build_index_route' => array('onBuildIndexRoute', 0),
        );
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoSliderBundle:Slide:table.html.twig');
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {

    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {
        $event->setBuilder(null);
    }
}
