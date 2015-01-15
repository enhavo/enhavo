<?php
/**
 * AdminInterface.php
 *
 * @since 30/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;


use Symfony\Component\Routing\Route;
use esperanto\AdminBundle\Model\Route as RouteModel;

interface Config
{
    public function setParameter($name, $value);
    public function getParameter($name);
    public function getParameters();
    public function resetParameters();
    public function deleteParameter($name);

    public function setTab($name, $label, $template);
    public function getTab($name);
    public function getTabs();
    public function resetTabs();
    public function deleteTab($name);

    public function setTemplate($name, $value);
    public function getTemplate($name);
    public function getTemplates();
    public function resetTemplates();
    public function deleteTemplate($name);

    public function setController($name, $controller);
    public function getController($name);
    public function getControllers();
    public function resetController();
    public function deleteController($name);

    public function setJavascript($name, $value, $depends = array());
    public function getJavascript($name);
    public function getJavascripts();
    public function resetJavascripts();
    public function deleteJavascript($name);

    public function setStylesheet($name, $value, $depends = array());
    public function getStylesheet($name);
    public function getStylesheets();
    public function resetStylesheets();
    public function deleteStylesheet($name);

    public function setRoute($name, $routeName, Route $route);

    /**
     *
     * @param $name
     * @return RouteModel|null
     */
    public function getRoute($name);
    public function getRoutes();
    public function resetRoutes();
    public function deleteRoute($name);

    public function setMenu($name, $menu);
    public function getMenu($name);
    public function getMenus();
    public function resetMenus();
    public function deleteMenu($name);
} 