<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\NewsBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\Container;

class AdminBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_news.news.build_index_route' => array('onBuildIndexRoute', 0),
            'esperanto_news.news.build_create_route' => array('onBuildCreateRoute', 0),
            'esperanto_news.news.build_delete_route' => array('onBuildDeleteRoute', 0),
            'esperanto_news.news.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_news.news.build_edit_route' => array('onBuildEditRoute', 0),
            'esperanto_news.news.build_menu' => array('onBuildMenu', 0),
            'esperanto_news.news.pre_build' => array('onPreBuild', 0),
            'esperanto_news.news.post_build' => array('onPostBuild', 0),
        );
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array(
            'sticky' => 'desc',
            'publication_date' => 'desc'
        ));
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array(
            'sticky' => 'desc',
            'publication_date' => 'desc'
        ));
        $event->getBuilder()->setTemplate('esperantoNewsBundle:Resource:table.html.twig');
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

    public function onBuildDeleteRoute(RouteBuilderEvent $event)
    {
        return;
    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {
        return;
    }

    public function onPreBuild(BuilderEvent $event)
    {
        return;
    }

    public function onPostBuild(BuilderEvent $event)
    {
        return;
    }

    protected function getTabViewBuilder()
    {
        $viewBuilder = new DialogViewBuilder();
        $viewBuilder->setTab('news', 'tab.label.news', 'esperantoNewsBundle:Tab:news.html.twig');
        $viewBuilder->setTab('content', 'tab.label.content', 'esperantoNewsBundle:Tab:content.html.twig');
        $viewBuilder->setTab('seo', 'tab.label.seo', 'esperantoNewsBundle:Tab:seo.html.twig');

        $previewRoute = $this->container->getParameter('esperanto_news.news_route');
        $viewBuilder->setParameter('preview_route', $previewRoute);

        return $viewBuilder;
    }
}