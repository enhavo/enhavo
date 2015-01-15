<?php
/**
 * AbstractRouteBuilder.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Builder\Route;

use esperanto\AdminBundle\Builder\View\ViewBuilder;
use Symfony\Component\Routing\Route;

abstract class AbstractRouteBuilder
{
    /**
     * @var Route
     */
    protected $route;

    /**
     * @var string
     */
    private $routeName;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $admin;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $methods = array();

    /**
     * @var null
     */
    protected $viewBuilder;

    /**
     * @var bool
     */
    protected $isExpose = false;

    public function __construct()
    {
        $this->viewBuilder = new ViewBuilder();
    }

    public function allowGetMethod()
    {
        if(!in_array('GET', $this->methods)) {
            $this->methods[] = 'GET';
        }
        return $this;
    }

    public function allowPostMethod()
    {
        if(!in_array('POST', $this->methods)) {
            $this->methods[] = 'POST';
        }
        return $this;
    }

    public function allowDeleteMethod()
    {
        if(!in_array('DELETE', $this->methods)) {
            $this->methods[] = 'DELETE';
        }
        return $this;
    }

    public function allowExpose()
    {
        $this->isExpose = true;
        return $this;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
        return $this;
    }

    public function setDefault($name, $value)
    {
        $this->defaults[$name] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function getRoute()
    {
        $pattern = $this->pattern;
        $defaults =  $this->processDefaults($this->defaults);
        $requirements = array();
        $options = $this->processOptions($this->options);
        $host = '';
        $schemes = array();
        $methods = $this->methods;
        $condition = null;

        return new Route($pattern, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
    }

    protected function processDefaults($defaults)
    {
        if($this->controller) {
            $defaults['_controller'] = sprintf('%s:%s', $this->getController(), $this->getAction());
        }

        if($this->admin) {
            $defaults['_admin'] = $this->admin;
        }

        return $defaults;
    }

    protected function processOptions($options)
    {
        if($this->isExpose) {
            $options['expose'] = 'true';
        }

        return $options;
    }

    /**
     * @return string
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param string $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * @return ViewBuilder
     */
    public function getViewBuilder()
    {
        return $this->viewBuilder;
    }

    /**
     * @param ViewBuilder $viewBuilder
     */
    public function setViewBuilder($viewBuilder)
    {
        $this->viewBuilder = $viewBuilder;
        return $this;
    }
}