<?php

namespace esperanto\ProjectBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_page.page.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_page.page.build_edit_route' => array('onBuildEditRoute', 0),
            'esperanto_page.page.build_index_route' => array('onBuildIndexRoute', 0),
        );
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoProjectBundle:Content:table.html.twig');
    }

    public function onBuildEditRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoProjectBundle:Content:edit.html.twig');
        //$viewBuilder = $this->getTabViewBuilder();
        //$event->getBuilder()->setViewBuilder($viewBuilder);
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoProjectBundle:Content:index.html.twig');
    }

    protected function getTabViewBuilder()
    {
        $viewBuilder = new DialogViewBuilder();
        $viewBuilder->setTab('page', 'tab.label.page', 'esperantoProjectBundle:Tab:page.html.twig');
        $viewBuilder->setTab('content', 'tab.label.content', 'esperantoProjectBundle:Tab:content.html.twig');
        //ToDo: Preview should be defined in sub bundle or be a config var
        //$viewBuilder->setParameter('preview_route', 'how_to_video_main_page_show');
        return $viewBuilder;
    }

}