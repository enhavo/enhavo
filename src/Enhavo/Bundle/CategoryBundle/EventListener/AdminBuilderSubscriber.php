<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\CategoryBundle\EventListener;

use enhavo\AdminBundle\Builder\Route\SyliusRouteBuilder;
use enhavo\AdminBundle\Builder\View\ViewBuilder;
use enhavo\AdminBundle\Event\BuilderEvent;
use enhavo\AdminBundle\Event\MenuBuilderEvent;
use enhavo\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_category.collection.build_index_route' => array('resetRoute', 0),
            'enhavo_category.collection.build_create_route' => array('resetRoute', 0),
            'enhavo_category.collection.build_delete_route' => array('resetRoute', 0),
            'enhavo_category.collection.build_table_route' => array('resetRoute', 0),
            'enhavo_category.collection.build_edit_route' => array('resetRoute', 0),
            'enhavo_category.collection.build_menu' => array('onBuildMenu', 0),
            'enhavo_category.collection.post_build' => array('onPostBuild', 0),
        );
    }

    public function resetRoute(RouteBuilderEvent $event)
    {
        $event->setBuilder(null);
    }

    public function onBuildMenu(MenuBuilderEvent $event)
    {
        $event->setBuilder(null);
    }

    public function onPostBuild(BuilderEvent $event)
    {
        $routeBuilder = new SyliusRouteBuilder();

        $view = new ViewBuilder();
        $view->setParameter('manage_route', 'enhavo_category_collection_manage');

        $routeBuilder
            ->setRouteName('enhavo_category_collection_manage')
            ->setPattern('/category/collection/{name}/manage')
            ->allowGetMethod()
            ->allowPostMethod()
            ->allowExpose()
            ->setController('enhavo_category.controller.collection')
            ->setAction('updateAction')
            ->setAdmin('enhavo_category.admin.collection')
            ->setViewBuilder($view)
            ->setTemplate('enhavoCategoryBundle:Resource:manage.html.twig');

        $event->getBuilder()->addRouteBuilder($routeBuilder);
    }
}