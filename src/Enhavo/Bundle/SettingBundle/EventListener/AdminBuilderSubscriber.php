<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\EventListener;

use Enhavo\Bundle\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Enhavo\Bundle\AdminBundle\Builder\Route\SyliusRouteBuilder;
use Enhavo\Bundle\AdminBundle\Builder\View\ViewBuilder;
use Enhavo\Bundle\AdminBundle\Event\BuilderEvent;
use Enhavo\Bundle\AdminBundle\Event\MenuBuilderEvent;

class AdminBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_setting.setting.build_index_route' => array('resetRoute', 0),
            'enhavo_setting.setting.build_create_route' => array('resetRoute', 0),
            'enhavo_setting.setting.build_delete_route' => array('resetRoute', 0),
            'enhavo_setting.setting.build_table_route' => array('resetRoute', 0),
            'enhavo_setting.setting.build_edit_route' => array('resetRoute', 0),
            'enhavo_setting.setting.build_menu' => array('onBuildMenu', 0),
            'enhavo_setting.setting.post_build' => array('onPostBuild', 0),
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
        $view->setParameter('manage_route', 'enhavo_setting_setting_manage');

        $routeBuilder
            ->setRouteName('enhavo_setting_setting_manage')
            ->setPattern('/setting/setting/{name}/manage')
            ->allowGetMethod()
            ->allowPostMethod()
            ->allowExpose()
            ->setController('enhavo_setting.controller.setting')
            ->setAction('updateAction')
            ->setAdmin('enhavo_setting.admin.setting')
            ->setViewBuilder($view)
            ->setTemplate('enhavoSettingBundle:Resource:manage.html.twig');

        $event->getBuilder()->addRouteBuilder($routeBuilder);
    }
}