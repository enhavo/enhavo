<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Viewer\TableViewer;
use Twig_Environment;

class BatchActionRender extends \Twig_Extension
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
            new \Twig_SimpleFunction('batch_action_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($viewer, $cssClass='batch-actions')
    {
        if (get_class($viewer) == 'Enhavo\Bundle\AppBundle\Viewer\TableViewer') {
            /** @var TableViewer $viewer */
            if (!$viewer->getHasBatchActions()) {
                return "";
            }
            return $this->twigEnvironment->render($this->template, array('viewer' => $viewer, 'cssClass' => $cssClass));
        }
        return "";
    }

    public function getName()
    {
        return 'batch_action_render';
    }
}
