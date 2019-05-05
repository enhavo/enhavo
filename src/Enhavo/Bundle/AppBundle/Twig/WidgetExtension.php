<?php

namespace Enhavo\Bundle\AppBundle\Twig;

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

    public function __construct(WidgetManager $widgetManager)
    {
        $this->widgetManager = $widgetManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('widget', array($this, 'render'), array('is_safe' => array('html')))
        );
    }

    public function renderWidget($type, $options, $resource = null)
    {
        $widget = $this->widgetManager->getWidget($type, $options);
        $data = $widget->createViewData($resource);
        $this->container->get('templating')->render($widget->getTemplate(), $data);
    }
}
