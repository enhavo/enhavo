<?php

namespace esperanto\AdminBundle\Twig;

use Pagerfanta\Pagerfanta;
use Twig_Environment;

class PaginationRender extends \Twig_Extension
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
            new \Twig_SimpleFunction('pagination_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(Pagerfanta $pagination)
    {
        return $this->twigEnvironment->render($this->template, array('pagination' => $pagination));
    }

    public function getName()
    {
        return 'pagination_render';
    }
}