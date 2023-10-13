<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
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
     * @var TemplateResolver
     */
    private $templateResolver;

    public function __construct(WidgetManager $widgetManager, TemplateResolver $templateResolver)
    {
        $this->widgetManager = $widgetManager;
        $this->templateResolver = $templateResolver;
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
        $template = $this->templateResolver->resolve($widget->getTemplate());
        $content = $this->container->get('twig')->render($template, $data);
        return $content;
    }
}
