<?php

namespace esperanto\DownloadBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DownloadBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_download.download.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_download.download.build_index_route' => array('onBuildIndexRoute', 0),
        );
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoDownloadBundle:Resource:table.html.twig');
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoDownloadBundle:Resource:index.html.twig');
    }
}