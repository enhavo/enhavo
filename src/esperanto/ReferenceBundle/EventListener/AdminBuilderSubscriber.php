<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ReferenceBundle\EventListener;

use esperanto\AdminBundle\Builder\View\DialogViewBuilder;
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
            'esperanto_reference.reference.build_index_route' => array('onBuildIndexRoute', 0),
            'esperanto_reference.reference.build_create_route' => array('onBuildCreateRoute', 0),
            'esperanto_reference.reference.build_table_route' => array('onBuildTableRoute', 0),
            'esperanto_reference.reference.build_edit_route' => array('onBuildEditRoute', 0),
        );
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setSorting(array('order' => 'ASC'));
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
        $viewBuilder->setTab('overview', 'tab.label.overview', 'esperantoReferenceBundle:Tab:overview.html.twig');
        $viewBuilder->setTab('details', 'tab.label.details', 'esperantoReferenceBundle:Tab:details.html.twig');
        $viewBuilder->setTab('category', 'tab.label.category', 'esperantoReferenceBundle:Tab:category.html.twig');
        $viewBuilder->setTab('seo', 'tab.label.seo', 'esperantoReferenceBundle:Tab:seo.html.twig');

        $previewRoute = $this->container->getParameter('esperanto_reference.reference_route');
        $viewBuilder->setParameter('reference_route', $previewRoute);

        return $viewBuilder;
    }
}