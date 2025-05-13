<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            new TwigFunction('widget', [$this, 'renderWidget'], ['is_safe' => ['html']]),
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
