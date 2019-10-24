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
        return [
            new TwigFunction('widget', [$this, 'renderWidget'], ['is_safe' => ['html']])
        ];
    }

    public function renderWidget($type, $options = [], $resource = null): string
    {
        $widget = $this->widgetManager->getWidget($type, $options);
        $data = $widget->createViewData($resource);
        $template = $this->templateManager->getTemplate($widget->getTemplate());
        $content = $this->container->get('twig')->render($template, $data);
        return $content;
    }
}
