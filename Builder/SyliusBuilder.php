<?php
/**
 * SyliusBuilder.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Builder;

use esperanto\AdminBundle\Admin\ConfigContainer;
use esperanto\AdminBundle\Builder\Menu\MenuBuilder;
use esperanto\AdminBundle\Event\MenuBuilderEvent;
use esperanto\AdminBundle\Event\BuilderEvent;
use esperanto\AdminBundle\Event\RouteBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use esperanto\AdminBundle\Builder\Route\SyliusRouteBuilder;

class SyliusBuilder
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var string
     */
    protected $companyName;

    /**
     * @var string
     */
    protected $bundleName;

    /**
     * @var string
     */
    protected $resourceName;

    /**
     * @var string
     */
    protected $defaultController;

    /**
     * @var string
     */
    protected $applicationName;

    /**
     * @var int
     */
    protected $defaultPagination;

    /**
     * @var array
     */
    protected $routeBuilders = array();

    /**
     * @var string
     */
    protected $defaultTemplatePattern;

    public function __construct(EventDispatcherInterface $eventDispatcher, $companyName, $bundleName, $resourceName)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->companyName = $companyName;
        $this->bundleName = $bundleName;
        $this->resourceName = $resourceName;
        $this->applicationName = sprintf('%s_%s', $this->companyName, $this->bundleName);

        $this->defaultController = sprintf('%s.controller.%s',
            $this->applicationName,
            $this->resourceName
        );

        $this->defaultTemplatePattern = 'esperantoAdminBundle:Resource:%s.html.twig';
        $this->defaultAdmin = sprintf('%s.admin.%s', $this->applicationName, $this->resourceName);
        $this->defaultPagination = 10;
    }

    public function addRouteBuilder($routeBuilder)
    {
        $this->routeBuilders[] = $routeBuilder;
    }

    public function getConfig()
    {
        $this->dispatchBuilderEvent('pre', new BuilderEvent($this));

        $createRouteBuilder = $this->getCreateRouteBuilder();
        $indexRouteBuilder = $this->getIndexRouteBuilder();
        $tableRouteBuilder = $this->getTableRouteBuilder();
        $editRouteBuilder = $this->getEditRouteBuilder();
        $deleteRouteBuilder = $this->getDeleteRouteBuilder();

        $config = new ConfigContainer();

        $config->setParameter('company', $this->companyName);
        $config->setParameter('entity', $this->resourceName);
        $config->setParameter('bundle', $this->bundleName);
        $config->setParameter('paginate', $this->defaultPagination);

        if($indexRouteBuilder) {
            $config->setRoute('index', $indexRouteBuilder->getRouteName(), $indexRouteBuilder->getRoute());
            $config->setTemplate('index', $indexRouteBuilder->getTemplate());
            if($indexRouteBuilder->getViewBuilder()) {
                $indexRouteBuilder->getViewBuilder()->processConfig($config);
            }
        }

        if($tableRouteBuilder) {
            $config->setRoute('table', $tableRouteBuilder->getRouteName(), $tableRouteBuilder->getRoute());
            $config->setTemplate('table', $tableRouteBuilder->getTemplate());
            if($tableRouteBuilder->getViewBuilder()) {
                $tableRouteBuilder->getViewBuilder()->processConfig($config);
            }
        }

        if($createRouteBuilder) {
            $config->setRoute('create', $createRouteBuilder->getRouteName(), $createRouteBuilder->getRoute());
            $config->setTemplate('create', $createRouteBuilder->getTemplate());
            if($createRouteBuilder->getViewBuilder()) {
                $createRouteBuilder->getViewBuilder()->processConfig($config);
            }
        }

        if($editRouteBuilder) {
            $config->setRoute('edit', $editRouteBuilder->getRouteName(), $editRouteBuilder->getRoute());
            $config->setTemplate('edit', $editRouteBuilder->getTemplate());
            if($editRouteBuilder->getViewBuilder()) {
                $editRouteBuilder->getViewBuilder()->processConfig($config);
            }
        }

        if($deleteRouteBuilder) {
            $config->setRoute('delete', $deleteRouteBuilder->getRouteName(), $deleteRouteBuilder->getRoute());
            if($deleteRouteBuilder->getViewBuilder()) {
                $deleteRouteBuilder->getViewBuilder()->processConfig($config);
            }
        }

        $config->setTemplate('form', sprintf($this->defaultTemplatePattern, 'form'));

        $menuBuilder = $this->getMenuBuilder($indexRouteBuilder);
        if($menuBuilder) {
            $config->setMenu('default', $menuBuilder->getMenu());
        }

        $this->dispatchBuilderEvent('post', new BuilderEvent($this));

        /** @var $routeBuilder SyliusRouteBuilder */
        foreach($this->routeBuilders as $routeBuilder) {
            $config->setRoute(microtime(true), $routeBuilder->getRouteName(), $routeBuilder->getRoute());
            if($routeBuilder->getViewBuilder()) {
                $routeBuilder->getViewBuilder()->processConfig($config);
            }
        }

        return $config;
    }

    /**
     * @param SyliusRouteBuilder|null $routeBuilder
     * @return MenuBuilder
     */
    protected function getMenuBuilder($routeBuilder)
    {
        /** @var $routeBuilder SyliusRouteBuilder */
        $menuBuilder = new MenuBuilder();
        if($routeBuilder) {
            $menuBuilder->setRouteName($routeBuilder->getRouteName());
            $menuBuilder->setIconName($this->resourceName);
            $menuBuilder->setLabel('label.'.$this->resourceName);
        }

        $event = $this->dispatchMenuBuilderEvent(new MenuBuilderEvent($menuBuilder));
        return $event->getBuilder();
    }

    /**
     * @return SyliusRouteBuilder
     */
    protected function getCreateRouteBuilder()
    {
        $routeBuilder = new SyliusRouteBuilder();
        $routeBuilder
            ->setRouteName(sprintf('%s_%s_%s', $this->applicationName, $this->resourceName, 'create'))
            ->setPattern(sprintf('/%s/%s/create', $this->bundleName, $this->resourceName))
            ->allowGetMethod()
            ->allowPostMethod()
            ->allowExpose()
            ->setController($this->defaultController)
            ->setAction('createAction')
            ->setAdmin($this->defaultAdmin)
            ->setTemplate(sprintf($this->defaultTemplatePattern, 'create'));

        $event = $this->dispatchRouteBuilderEvent('create', new RouteBuilderEvent($routeBuilder));
        return $event->getBuilder();
    }

    /**
     * @return SyliusRouteBuilder
     */
    protected function getIndexRouteBuilder()
    {
        $routeBuilder = new SyliusRouteBuilder();
        $routeBuilder
            ->setRouteName(sprintf('%s_%s_%s', $this->applicationName, $this->resourceName, 'index'))
            ->setPattern(sprintf('/%s/%s/{page}/list', $this->bundleName, $this->resourceName))
            ->setDefault('page', 1)
            ->setPaginate($this->defaultPagination)
            ->allowGetMethod()
            ->allowExpose()
            ->setController($this->defaultController)
            ->setAction('indexAction')
            ->setAdmin($this->defaultAdmin)
            ->setTemplate(sprintf($this->defaultTemplatePattern, 'index'));

        $event = $this->dispatchRouteBuilderEvent('index', new RouteBuilderEvent($routeBuilder));
        return $event->getBuilder();
    }

    /**
     * @return SyliusRouteBuilder
     */
    protected function getTableRouteBuilder()
    {
        $routeBuilder = new SyliusRouteBuilder();
        $routeBuilder
            ->setRouteName(sprintf('%s_%s_%s', $this->applicationName, $this->resourceName, 'table'))
            ->setPattern(sprintf('/%s/%s/{page}/table', $this->bundleName, $this->resourceName))
            ->setDefault('page', 1)
            ->setPaginate($this->defaultPagination)
            ->allowGetMethod()
            ->allowExpose()
            ->setController($this->defaultController)
            ->setAction('indexAction')
            ->setAdmin($this->defaultAdmin)
            ->setTemplate(sprintf($this->defaultTemplatePattern, 'table'));

        $event = $this->dispatchRouteBuilderEvent('table', new RouteBuilderEvent($routeBuilder));
        return $event->getBuilder();
    }

    /**
     * @return SyliusRouteBuilder
     */
    protected function getEditRouteBuilder()
    {
        $routeBuilder = new SyliusRouteBuilder();
        $routeBuilder
            ->setRouteName(sprintf('%s_%s_%s', $this->applicationName, $this->resourceName, 'edit'))
            ->setPattern(sprintf('/%s/%s/{id}/edit', $this->bundleName, $this->resourceName))
            ->allowGetMethod()
            ->allowPostMethod()
            ->allowExpose()
            ->setController($this->defaultController)
            ->setAction('updateAction')
            ->setAdmin($this->defaultAdmin)
            ->setTemplate(sprintf($this->defaultTemplatePattern, 'edit'));

        $event = $this->dispatchRouteBuilderEvent('edit', new RouteBuilderEvent($routeBuilder));
        return $event->getBuilder();
    }

    /**
     * @return SyliusRouteBuilder
     */
    protected function getDeleteRouteBuilder()
    {
        $routeBuilder = new SyliusRouteBuilder();
        $routeBuilder
            ->setRouteName(sprintf('%s_%s_%s', $this->applicationName, $this->resourceName, 'delete'))
            ->setPattern(sprintf('/%s/%s/{id}/delete', $this->bundleName, $this->resourceName))
            ->allowDeleteMethod()
            ->allowExpose()
            ->setController($this->defaultController)
            ->setAction('deleteAction')
            ->setAdmin($this->defaultAdmin)
            ->setTemplate(sprintf($this->defaultTemplatePattern, 'delete'));

        $event = $this->dispatchRouteBuilderEvent('delete', new RouteBuilderEvent($routeBuilder));
        return $event->getBuilder();
    }
    /**
     * @param $name string
     * @param $event RouteBuilderEvent
     * @return RouteBuilderEvent
     */
    protected function dispatchRouteBuilderEvent($name, $event)
    {
        $eventName = sprintf('%s.%s.build_%s_route', $this->applicationName, $this->resourceName, $name);
        $this->eventDispatcher->dispatch($eventName, $event);
        return $event;
    }

    /**
     * @param $event MenuBuilderEvent
     * @return RouteBuilderEvent
     */
    protected function dispatchMenuBuilderEvent($event)
    {
        $eventName = sprintf('%s.%s.build_menu', $this->applicationName, $this->resourceName);
        $this->eventDispatcher->dispatch($eventName, $event);
        return $event;
    }

    /**
     * @param $name string
     * @param $event BuilderEvent
     * @return RouteBuilderEvent
     */
    protected function dispatchBuilderEvent($name, $event)
    {
        $eventName = sprintf('%s.%s.%s_build', $this->applicationName, $this->resourceName, $name);
        $this->eventDispatcher->dispatch($eventName, $event);
        return $event;
    }
}