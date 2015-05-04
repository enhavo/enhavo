<?php
/**
 * Admin.php
 *
 * @since 02/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;

use esperanto\AdminBundle\Builder\SyliusBuilder;
use esperanto\AdminBundle\Model\AdminView;
use esperanto\AdminBundle\Model\Route;
use esperanto\AdminBundle\Model\View;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\RouteCollection;
use esperanto\AdminBundle\Admin\Menu;

class BaseAdmin implements Admin
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var RouteCollection
     */
    protected $routeCollection;

    /**
     * @param Container $container
     * @param $company
     * @param $bundle
     * @param $entity
     */
    public function __construct(Container $container, $company, $bundle, $entity)
    {
        $this->container = $container;
        $this->company = $company;
        $this->bundle = $bundle;
        $this->entity = $entity;
    }

    public function init()
    {
        $builder = new SyliusBuilder(
            $this->container->get('event_dispatcher'),
            $this->company,
            $this->bundle,
            $this->entity,
            $this->container->getParameter('esperanto_admin.stylesheets'),
            $this->container->getParameter('esperanto_admin.javascripts')
        );
        $this->config = $builder->getConfig();
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        if($this->routeCollection === null) {
            $this->routeCollection = new RouteCollection();
            /** @var $route Route */
            foreach($this->config->getRoutes() as $route) {
                $this->routeCollection->add($route->getRouteName(), $route->getRoute());
            }
        }

        return $this->routeCollection;
    }

    /**
     * @return AdminView
     */
    public function createView()
    {
        $view = new View();

        if($this->config->getRoute('create')) {
            $view->setParameter('create_route', $this->config->getRoute('create')->getRouteName());
        }

        if($this->config->getRoute('table')) {
            $view->setParameter('table_template', $this->config->getTemplate('table'));
            $view->setParameter('table_route', $this->config->getRoute('table')->getRouteName());
        }

        if($this->config->getRoute('index')) {
            $view->setParameter('index_route', $this->config->getRoute('index')->getRouteName());
        }

        if($this->config->getRoute('edit')) {
            $view->setParameter('edit_route', $this->config->getRoute('edit')->getRouteName());
        }

        if($this->config->getRoute('delete')) {
            $view->setParameter('delete_route', $this->config->getRoute('delete')->getRouteName());
        }

        $view->setParameter('form_template', $this->config->getTemplate('form'));
        $view->setParameter('add_button_text', sprintf('button.add_%s', $this->entity));
        $view->setParameter('empty_table_text', sprintf('text.empty_%s', $this->entity));
        $view->setParameter('tabs', $this->config->getTabs());

        foreach($this->config->getParameters() as $key => $value) {
            $view->setParameter($key, $value);
        }

        return $view;
    }

    public function getMenu()
    {
        /** @var $oldMenu Menu */
        $oldMenu = $this->config->getMenu('default');
        if($oldMenu) {
            $menu = $this->container->get('knp_menu.factory')->createItem(
                $oldMenu->getName(), array(
                    'route' => $oldMenu->getRouteName()
                )
            );
            $menu->setAttribute('class', sprintf('menu-icon-%s', $oldMenu->getIconName()));
            return $menu;
        }
        return null;
    }

    public function isActionGranted($action)
    {
        $permissionCheck = $this->container->getParameter('esperanto_admin.permission_check');
        if(!$permissionCheck) {
            return true;
        }

        $role = strtoupper(sprintf('ROLE_%s_%s_ADMIN_%s_%s',
            $this->company,
            $this->bundle,
            $this->entity,
            $action
        ));

        if ($this->container->get('security.context')->isGranted($role)) {
            return true;
        }

        return false;
    }
}