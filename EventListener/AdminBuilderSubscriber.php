<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\CategoryBundle\EventListener;

use esperanto\AdminBundle\Builder\Route\SyliusRouteBuilder;
use esperanto\AdminBundle\Builder\View\ViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_category.collection.build_index_route' => array('resetRoute', 0),
            'esperanto_category.collection.build_create_route' => array('resetRoute', 0),
            'esperanto_category.collection.build_delete_route' => array('resetRoute', 0),
            'esperanto_category.collection.build_table_route' => array('resetRoute', 0),
            'esperanto_category.collection.build_edit_route' => array('resetRoute', 0),
            'esperanto_category.collection.build_menu' => array('onBuildMenu', 0),
            'esperanto_category.collection.post_build' => array('onPostBuild', 0),
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
        $view->setParameter('manage_route', 'esperanto_category_collection_manage');

        $routeBuilder
            ->setRouteName('esperanto_category_collection_manage')
            ->setPattern('/category/collection/{name}/manage')
            ->allowGetMethod()
            ->allowPostMethod()
            ->allowExpose()
            ->setController('esperanto_category.controller.collection')
            ->setAction('updateAction')
            ->setAdmin('esperanto_category.admin.collection')
            ->setViewBuilder($view)
            ->setTemplate('esperantoCategoryBundle:Resource:manage.html.twig');

        $event->getBuilder()->addRouteBuilder($routeBuilder);
    }
}