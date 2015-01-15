<?php

namespace esperanto\AdminBundle\Twig;

use esperanto\AdminBundle\View\TabCollection;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Twig_Environment;

class TabRender extends \Twig_Extension
{
    /** @var $templateEngine Twig_Environment */
    protected $twigEnvironment;

    protected $template = '';

    public function initRuntime(Twig_Environment $environment)
    {
        $this->twigEnvironment = $environment;
    }

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tab_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(TabCollection $tabs)
    {
        return $this->twigEnvironment->render($this->template, array('tabs' => $tabs));
    }

    public function getName()
    {
        return 'tab_render';
    }
} 