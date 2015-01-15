<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\PageBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_page.page.build_create_route' => array('onBuildCreateRoute', 0),
            'esperanto_page.page.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_page.page.build_edit_route' => array('onBuildEditRoute', 0),
        );
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('esperantoPageBundle:Resource:table.html.twig');
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
        $viewBuilder->setTab('page', 'tab.label.page', 'esperantoPageBundle:Tab:page.html.twig');
        $viewBuilder->setTab('content', 'tab.label.content', 'esperantoPageBundle:Tab:content.html.twig');
        $viewBuilder->setTab('seo', 'tab.label.seo', 'esperantoPageBundle:Tab:seo.html.twig');
        //ToDo: Preview should be defined in sub bundle or be a config var
        $viewBuilder->setParameter('preview_route', 'how_to_video_main_page_show');
        return $viewBuilder;
    }
}