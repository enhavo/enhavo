<?php
/**
 * AdminFactory.php
 *
 * @since 30/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;

use Symfony\Component\Routing\Route;

class AdminFactory
{
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
     * @var Event
     */
    protected $event;

    public function __construct($company, $bundle, $entity, Event $event = null)
    {
        $this->event = $event;
        $this->company = $company;
        $this->bundle = $bundle;
        $this->entity = $entity;
    }

    /**
     * @return Config
     */
    public function createAdminConfig()
    {
        $config = new ConfigContainer();

        $this->buildParameters($config);
        $this->buildTabs($config);
        $this->buildTemplates($config);
        $this->buildControllers($config);
        $this->buildJavascripts($config);
        $this->buildStylesheets($config);
        $this->buildRoutes($config);
        $this->buildMenus($config);

        return $config;
    }

    protected function buildParameters(Config $config)
    {
        $this->event->preSetParameters($config);
        $config->setParameter('company', $this->company);
        $config->setParameter('entity', $this->entity);
        $config->setParameter('bundle', $this->bundle);
        $config->setParameter('paginate', 10);
        $this->event->postSetParameters($config);
    }

    protected function buildTabs(Config $config)
    {
        $this->event->preSetTabs($config);

        $this->event->postSetTabs($config);
    }

    protected function buildTemplates(Config $config)
    {
        $this->event->preSetTemplates($config);
        $defaultTemplate = 'esperantoAdminBundle:Resource:%s.html.twig';
        $templates = array(
            'edit',
            'table',
            'create',
            'index',
            'form'
        );
        foreach($templates as $template) {
            $config->setTemplate($template, sprintf($defaultTemplate, $template));
        }
        $this->event->postSetTemplates($config);
    }

    protected function buildControllers(Config $config)
    {
        $this->event->preSetControllers($config);
        $controller = $this->company.'_'.$this->bundle.'.controller.'.$this->entity.':';
        $config->setController('create', $controller.'createAction');
        $config->setController('table', $controller.'indexAction');
        $config->setController('index', $controller.'indexAction');
        $config->setController('edit', $controller.'updateAction');
        $config->setController('delete', $controller.'deleteAction');
        $this->event->postSetControllers($config);
    }

    protected function buildJavascripts(Config $config)
    {
        $this->event->preSetJavascripts($config);

        $config->setJavascript('admin_bootstrap', '@esperantoAdminBundle/Resources/public/js/bootstrap.js', array(
            'admin_form',
            'admin_admin'
        ));
        $config->setJavascript('admin_form', '@esperantoAdminBundle/Resources/public/js/classes/Form.js', array(
            'admin_validation_errors'
        ));
        $config->setJavascript('admin_admin', '@esperantoAdminBundle/Resources/public/js/classes/Admin.js');
        $config->setJavascript('admin_validation_errors', '@esperantoAdminBundle/Resources/public/js/classes/ValidationError.js');

        $this->event->postSetJavascripts($config);
    }

    protected function buildStylesheets(Config $config)
    {
        $this->event->preSetStylesheets($config);
        $config->setStylesheet('admin_base', '@esperantoAdminBundle/Resources/public/css/base.js');
        $config->setStylesheet('admin_theme', '@esperantoAdminBundle/Resources/public/css/theme.js', array(
            'admin_base'
        ));
        $config->setStylesheet('admin_form', '@esperantoAdminBundle/Resources/public/css/form.js', array(
            'admin_theme'
        ));
        $config->setStylesheet('admin_editor', '@esperantoAdminBundle/Resources/public/css/form.js', array());

        $this->event->postSetStylesheets($config);
    }

    protected function buildRoutes(Config $config)
    {
        $this->event->preSetRoutes($config);

        $preRouteName = $this->company.'_'.$this->bundle.'_'.$this->entity.'_';

        $config->setRoute('index', $preRouteName.'index',
            $this->createRoute(
            '/'.$this->bundle.'/'.$this->entity.'/{page}/list',
            array(
                'page' => 1,
                '_controller' => $config->getController('index'),
                '_sylius' => array(
                    'paginate' => $config->getParameter('paginate'),
                    'template' => $config->getTemplate('index')
                )
            ),
             array('GET')
        ));

        $config->setRoute('table', $preRouteName.'table',
            $this->createRoute(
            '/'.$this->bundle.'/'.$this->entity.'/{page}/table',
            array(
                'page' => 1,
                '_controller' => $config->getController('table'),
                '_sylius' => array(
                    'paginate' => $config->getParameter('paginate'),
                    'template' => $config->getTemplate('table')
                )
            ),
            array('GET')
        ));

        $config->setRoute('create', $preRouteName.'create',
            $this->createRoute(
            '/'.$this->bundle.'/'.$this->entity.'/create',
            array(
                'page' => 1,
                '_controller' => $config->getController('create'),
                '_sylius' => array(
                    'template' => $config->getTemplate('create')
                )
            ),
            array('GET', 'POST')
        ));

        $config->setRoute('edit', $preRouteName.'edit',
            $this->createRoute(
            '/'.$this->bundle.'/'.$this->entity.'/{id}/edit',
            array(
                '_controller' => $config->getController('edit'),
                '_sylius' => array(
                    'template' => $config->getTemplate('edit')
                )
            ),
            array('GET', 'POST')
        ));

        $config->setRoute('delete', $preRouteName.'delete',
            $this->createRoute(
            '/'.$this->bundle.'/'.$this->entity.'/{id}/delete',
            array(
                '_controller' => $config->getController('delete')
            ),
            array('DELETE')
        ));

        $this->event->postSetRoutes($config);
    }


    protected function createRoute($pattern, $defaults, $methods = array())
    {
        $requirements = array();
        $options = array(
            'expose' => true
        );
        $host = '';
        $schemes = array();
        $condition = null;
        $defaults['_admin'] = $this->company.'_'.$this->bundle.'.'.$this->entity.'_admin';
        return new Route($pattern, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
    }

    protected function buildMenus(Config $config)
    {
        $this->event->preSetMenus($config);
        if($config->getRoute('index')) {
            $menu = new Menu();
            $menu->setRouteName($config->getRoute('index')->getRouteName());
            $menu->setIconName($this->entity);
            $menu->setName('label.'.$this->entity);
            $config->setMenu('default', $menu);
        }
        $this->event->postSetMenus($config);
    }
}