<?php
/**
 * ConfigContainer.php
 *
 * @since 30/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;


use esperanto\AdminBundle\Model\Javascript;
use esperanto\AdminBundle\Model\Stylesheet;
use esperanto\AdminBundle\Model\Tab;
use esperanto\AdminBundle\Model\Route as ModelRoute;
use Symfony\Component\Routing\Route;

class ConfigContainer implements Config
{
    private $templates = array();
    private $parameters = array();
    private $controllers = array();
    private $javascripts = array();
    private $stylesheets = array();
    private $routes = array();
    private $menus = array();
    private $tabs = array();


    public function setParameter($name, $value)
    {
        return $this->setTarget($name, $value, $this->parameters);
    }

    public function getParameter($name)
    {
        return $this->getTarget($name, $this->parameters);
    }

    public function resetParameters()
    {
        return $this->resetTarget($this->parameters);
    }

    public function deleteParameter($name)
    {
        return $this->deleteTarget($name, $this->parameters);
    }

    public function setTemplate($name, $value)
    {
        return $this->setTarget($name, $value, $this->templates);
    }

    public function getTemplate($name)
    {
        return $this->getTarget($name, $this->templates);
    }

    public function resetTemplates()
    {
        return $this->resetTarget($this->templates);
    }

    public function deleteTemplate($name)
    {
        return $this->deleteTarget($name, $this->templates);
    }

    public function setController($name, $controller)
    {
        return $this->setTarget($name, $controller, $this->controllers);
    }

    public function getController($name)
    {
        return $this->getTarget($name, $this->controllers);
    }

    public function resetController()
    {
        return $this->resetTarget($this->controllers);
    }

    public function deleteController($name)
    {
        return $this->deleteTarget($name, $this->controllers);
    }

    public function setJavascript($name, $value, $depends = array())
    {
        $js = new Javascript();
        $js->setName($name);
        $js->setFile($value);
        foreach($depends as $dependency) {
            $js->addDependency($dependency);
        }

        return $this->setTarget($name, $js, $this->javascripts);
    }

    public function getJavascript($name)
    {
        return $this->getTarget($name, $this->javascripts);
    }

    public function resetJavascripts()
    {
        return $this->resetTarget($this->javascripts);
    }

    public function deleteJavascript($name)
    {
        return $this->deleteTarget($name, $this->javascripts);
    }

    public function setStylesheet($name, $value, $depends = array())
    {
        $css = new Stylesheet();
        $css->setName($name);
        $css->setFile($value);
        foreach($depends as $dependency) {
            $css->addDependency($dependency);
        }

        return $this->setTarget($name, $css, $this->stylesheets);
    }

    public function getStylesheet($name)
    {
        return $this->getTarget($name, $this->stylesheets);
    }

    public function resetStylesheets()
    {
        return $this->resetTarget($this->stylesheets);
    }

    public function deleteStylesheet($name)
    {
        return $this->deleteTarget($name, $this->stylesheets);
    }

    public function setRoute($name, $routeName, Route $route)
    {
        $modelRoute = new ModelRoute();
        $modelRoute->setRoute($route);
        $modelRoute->setRouteName($routeName);

        return $this->setTarget($name, $modelRoute, $this->routes);
    }

    public function getRoute($name)
    {
        return $this->getTarget($name, $this->routes);
    }

    public function resetRoutes()
    {
        return $this->resetTarget($this->routes);
    }

    public function deleteRoute($name)
    {
        return $this->deleteTarget($name, $this->routes);
    }

    public function setMenu($name, $menu)
    {
        return $this->setTarget($name, $menu, $this->menus);
    }

    public function getMenu($name)
    {
        return $this->getTarget($name, $this->menus);
    }

    public function resetMenus()
    {
        return $this->resetTarget($this->menus);
    }

    public function deleteMenu($name)
    {
        return $this->deleteTarget($name, $this->menus);
    }

    /**
     * @return array
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * @return array
     */
    public function getJavascripts()
    {
        return $this->javascripts;
    }

    /**
     * @return array
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    public function setTab($name, $label, $template)
    {
        $tab = new Tab();
        $tab->setLabel($label);
        $tab->setTemplate($template);

        return $this->setTarget($name, $tab, $this->tabs);
    }

    public function getTab($name)
    {
        return $this->getTarget($name, $this->tabs);
    }

    public function getTabs()
    {
        return $this->tabs;
    }

    public function resetTabs()
    {
        return $this->resetTarget($this->tabs);
    }

    public function deleteTab($name)
    {
        return $this->deleteTarget($name, $this->tabs);
    }


    protected function setTarget($name, $value, &$target)
    {
        $target[$name] = $value;
        return $this;
    }

    protected function getTarget($name, &$target)
    {
        if(array_key_exists($name, $target)) {
            return $target[$name];
        }
        return null;
    }

    protected function deleteTarget($name, &$target)
    {
        if(array_key_exists($name, $target)) {
            unset($target[$name]);
        }
        return $this;
    }

    protected function resetTarget(&$target)
    {
        foreach($target as $key => $value) {
            unset($target[$key]);
        }
        $target = array();
        return $this;
    }
} 