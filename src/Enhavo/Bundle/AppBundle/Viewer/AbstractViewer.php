<?php
/**
 * AbstractViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractViewer implements ContainerAwareInterface
{
    private $resource;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\Form\Form
     */
    private $form;

    /**
     * @var \Enhavo\Bundle\AppBundle\Config\ConfigParser
     */
    private $config;

    /**
     * @var string
     */
    private $bundlePrefix;

    /**
     * @var string
     */
    private $resourceName;

    /**
     * @var string[]
     */
    private $stylesheets;

    /**
     * @var string[]
     */
    private $javascripts;

    /**
     * @param mixed $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        $stylesheets = $this->container->getParameter('enhavo_app.stylesheets');
        $this->setStylesheets($stylesheets);

        $javascripts = $this->container->getParameter('enhavo_app.javascripts');
        $this->setJavascripts($javascripts);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    public function dispatchEvent()
    {

    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
        $this->config->setDefault($this->getDefaultConfig());
    }

    public function getDefaultConfig()
    {
        return array();
    }

    public function getParameters()
    {
        return $this->getTemplateVars();
    }

    public function getTheme()
    {
        return '::admin.html.twig';
    }

    protected function getTemplateVars()
    {
        $parameters = $this->getConfig()->get('parameters');
        if(!is_array($parameters)) {
            return array();
        }
        return $parameters;
    }

    public function getTemplate()
    {
        return 'EnhavoAppBundle:App:index.html.twig';
    }

    public function setBundlePrefix($bundlePrefix)
    {
        $this->bundlePrefix = $bundlePrefix;
    }

    public function setResourceName($resourceName)
    {
        $this->resourceName = $resourceName;
    }

    /**
     * @return string
     */
    public function getBundlePrefix()
    {
        return $this->bundlePrefix;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }

    public function getTranslationDomain()
    {
        $translationDomain = $this->getConfig()->get('translationDomain');
        return $translationDomain;
    }

    /**
     * @return string[]
     */
    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    /**
     * @param string[] $stylesheets
     */
    public function setStylesheets($stylesheets)
    {
        $this->stylesheets = $stylesheets;
    }

    /**
     * @return string[]
     */
    public function getJavascripts()
    {
        return $this->javascripts;
    }

    /**
     * @param string[] $javascripts
     */
    public function setJavascripts($javascripts)
    {
        $this->javascripts = $javascripts;
    }
}