<?php
/**
 * AdminBuilderSubscriber.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\SettingBundle\EventListener;

use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use esperanto\AdminBundle\Builder\Route\SyliusRouteBuilder;
use esperanto\AdminBundle\Builder\View\ViewBuilder;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\MenuBuilderEvent;

class AdminBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_setting.setting.build_index_route' => array('resetRoute', 0),
            'esperanto_setting.setting.build_create_route' => array('resetRoute', 0),
            'esperanto_setting.setting.build_delete_route' => array('resetRoute', 0),
            'esperanto_setting.setting.build_table_route' => array('resetRoute', 0),
            'esperanto_setting.setting.build_edit_route' => array('resetRoute', 0),
            'esperanto_setting.setting.build_menu' => array('onBuildMenu', 0),
            'esperanto_setting.setting.post_build' => array('onPostBuild', 0),
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
        $view->setParameter('manage_route', 'esperanto_setting_setting_manage');

        $routeBuilder
            ->setRouteName('esperanto_setting_setting_manage')
            ->setPattern('/setting/setting/{name}/manage')
            ->allowGetMethod()
            ->allowPostMethod()
            ->allowExpose()
            ->setController('esperanto_setting.controller.setting')
            ->setAction('updateAction')
            ->setAdmin('esperanto_setting.admin.setting')
            ->setViewBuilder($view)
            ->setTemplate('esperantoSettingBundle:Resource:manage.html.twig');

        $event->getBuilder()->addRouteBuilder($routeBuilder);
    }
}