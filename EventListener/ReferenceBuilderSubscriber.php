<?php

namespace esperanto\ProjectBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use esperanto\AdminBundle\Builder\View\ViewBuilder;

class ReferenceBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_reference.reference.build_index_route' => array('onBuildIndexRoute', 0),
            'esperanto_reference.reference.build_create_route' => array('onBuildCreateRoute', 0),
            'esperanto_reference.reference.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_reference.reference.build_edit_route' => array('onBuildEditRoute', 0),
        );
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array(
            'order' => 'asc'
        ));
        $event->getBuilder()->setTemplate('esperantoProjectBundle:Reference:Default/index.html.twig');
        $viewBuilder = new ViewBuilder();
        $viewBuilder->setParameter('category_route', 'esperanto_category_collection_manage');
        $event->getBuilder()->setViewBuilder($viewBuilder);
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array('order' => 'ASC'));
        $event->getBuilder()->setTemplate('esperantoReferenceBundle:Resource:table.html.twig');
    }

    public function onBuildCreateRoute(RouteBuilderEvent $event)
    {
        $viewBuilder = $this->getTabViewBuilder();
        $event->getBuilder()->setViewBuilder($viewBuilder);
    }

    public function onBuildEditRoute(RouteBuilderEvent $event)
    {
        $viewBuilder = $this->getTabViewBuilder();
        $event->getBuilder()->setViewBuilder($viewBuilder);
    }

    protected function getTabViewBuilder()
    {
        $viewBuilder = new DialogViewBuilder();
        $viewBuilder->setTab('overview', 'tab.label.overview', 'esperantoProjectBundle:Reference:Tab/overview.html.twig');
        $viewBuilder->setTab('details', 'tab.label.details', 'esperantoProjectBundle:Reference:Tab/details.html.twig');
        $viewBuilder->setTab('category', 'tab.label.category', 'esperantoProjectBundle:Reference:Tab/category.html.twig');
        $viewBuilder->setTab('seo', 'tab.label.seo', 'esperantoProjectBundle:Reference:Tab/seo.html.twig');
        //ToDo: Preview should be defined in sub bundle or be a config var
        $viewBuilder->setParameter('preview_route', 'esperanto_project_index');

        return $viewBuilder;
    }
}