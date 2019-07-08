<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\AppBundle\Widget\WidgetManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WidgetExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    /**
     * @var WidgetManager
     */
    private $widgetManager;

    /**
     * @var TemplateManager
     */
    private $templateManager;

    public function __construct(WidgetManager $widgetManager, TemplateManager $templateManager)
    {
        $this->widgetManager = $widgetManager;
        $this->templateManager = $templateManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('widget', array($this, 'renderWidget'), array('is_safe' => array('html')))
        );
    }

    public function renderWidget($type, $options = [], $resource = null): string
    {
        $widget = $this->widgetManager->getWidget($type, $options);
        $data = $widget->createViewData($resource);
        return $this->container->get('templating')->render($this->templateManager->getTemplate($widget->getTemplate()), $data);
    }
}
