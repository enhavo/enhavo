<?php
/**
 * HomepageAdmin.php
 *
 * @since 31/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;

use esperanto\AdminBundle\Admin\Admin as AdminInterface;
use esperanto\AdminBundle\Model\View;
use Symfony\Component\Routing\RouteCollection;
use Knp\Menu\FactoryInterface;
use esperanto\SettingBundle\Service\SettingService;

class HomepageAdmin implements AdminInterface
{
    protected $factory;
    protected $settingService;

    public function __construct(FactoryInterface $factory, SettingService $settingService)
    {
        $this->factory = $factory;
        $this->settingService = $settingService;
    }

    public function getRouteCollection()
    {
        $routeCollection = new RouteCollection();
        return $routeCollection;
    }

    public function createView()
    {
        $view = new View();
        $view->setParameter('slider_sort_route', 'esperanto_slider_slider_sort');
        return $view;
    }

    public function init()
    {

    }

    public function isActionGranted($action)
    {
        return true;
    }

    public function getMenu()
    {
        $menu = $this->factory->createItem('label.homepage', array(
            'route' => 'esperanto_admin_homepage_index'
        ));
        $menu->setAttribute('class', 'menu-icon-homepage');
        return $menu;
    }
}