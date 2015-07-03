<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Admin\AdminRegister;
use Knp\Menu\MenuItem;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Enhavo\Bundle\AppBundle\Admin\Admin;

class AdminMenuRender extends \Twig_Extension
{
    /**
     * @var AdminRegister
     */
    protected $adminRegister;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $menus = array();

    /**
     * @param Container $container
     * @param $template string
     */
    public function __construct(Container $container, $template)
    {
        $this->container = $container;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('admin_menu_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render()
    {
        if($this->templateEngine === null) {
            $this->templateEngine = $this->container->get('templating');
        }

        $menus = $this->container->get('enhavo_app.menu_loader')->getMenu();

        return $this->templateEngine->render($this->template, array(
            'menus' => $menus
        ));
    }

    public function createLogoutMenu()
    {
        $menu = $this->container->get('knp_menu.factory')->createItem('label.logout', array(
            'route' => 'fos_user_security_logout'
        ));
        $menu->setAttribute('class', 'menu-icon-logout');
        return $menu;
    }

    public function getName()
    {
        return 'admin_menu_render';
    }
} 