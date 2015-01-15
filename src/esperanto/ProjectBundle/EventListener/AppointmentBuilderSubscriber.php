<?php

namespace esperanto\ProjectBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppointmentBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_project.appointment.build_index_route' => array('onBuildIndexRoute', 0),
            'esperanto_project.appointment.build_table_route' => array('onBuildTableRoute', 0),
        );
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array(
            'date' => 'asc'
        ));
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array(
            'date' => 'asc'
        ));
        $event->getBuilder()->setTemplate('esperantoProjectBundle:Appointment:table.html.twig');
    }
}